<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CacheLog;
use App\Models\Category;
use App\Services\ProductCacheService;
use App\Services\CacheKeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Build the filtered / sorted product query
    protected function buildQuery(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category_id;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        $sort = $request->sort ?? 'id_asc';

        return Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($minPrice !== null && $minPrice !== '', function ($query) use ($minPrice) {
                $query->where('price', '>=', $minPrice);
            })
            ->when($maxPrice !== null && $maxPrice !== '', function ($query) use ($maxPrice) {
                $query->where('price', '<=', $maxPrice);
            })
            ->when($sort, function ($query) use ($sort) {
                return match ($sort) {
                    'name_asc' => $query->orderBy('name', 'asc'),
                    'name_desc' => $query->orderBy('name', 'desc'),
                    'price_asc' => $query->orderBy('price', 'asc'),
                    'price_desc' => $query->orderBy('price', 'desc'),
                    'id_desc' => $query->orderBy('id', 'desc'),
                    default => $query->orderBy('id', 'asc'),
                };
            });
    }

    // Product Listing + Search + Filter + Sort + Pagination (cached)
    public function index(Request $request)
    {
        $search = $request->search;
        $categoryId = $request->category_id;
        $minPrice = $request->min_price;
        $maxPrice = $request->max_price;
        $sort = $request->sort ?? 'id_asc';
        $page = $request->get('page', 1);

        $base = 'products_' . md5(implode('|', [
            $search, $categoryId, $minPrice, $maxPrice, $sort, $page,
        ]));

        $hit = ProductCacheService::has($base);

        if ($hit) {
            $products = ProductCacheService::get($base);

            CacheLog::create([
                'type' => 'HIT',
                'query' => $search,
            ]);
        } else {
            $start = microtime(true);

            $products = ProductCacheService::remember($base, function () use ($request) {
                return $this->buildQuery($request)->paginate(3);
            });

            $duration = round(microtime(true) - $start, 4);

            CacheLog::create([
                'type' => 'MISS',
                'query' => $search,
                'duration' => $duration,
            ]);
        }

        $totalProducts = ProductCacheService::remember('total_products', fn () => Product::count());
        $totalPrice = ProductCacheService::remember('total_price', fn () => Product::sum('price'));
        $categories = ProductCacheService::remember('categories_list', fn () => Category::orderBy('name')->get());

        return view('products.index', compact(
            'products',
            'categories',
            'search',
            'categoryId',
            'minPrice',
            'maxPrice',
            'sort',
            'totalProducts',
            'totalPrice'
        ));
    }

    // Create Page
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    // Store Product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Product::create($request->only('name', 'price', 'category_id'));

        ProductCacheService::bumpVersion();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Added Successfully');
    }

    // Edit Page
    public function edit($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    // Update Product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product = Product::withTrashed()->findOrFail($id);
        $product->update($request->only('name', 'price', 'category_id'));

        ProductCacheService::bumpVersion();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Updated Successfully');
    }

    // Delete Product (Soft Delete)
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        ProductCacheService::bumpVersion();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Deleted Successfully');
    }

    // ---------- Soft Deletes (Trash) ----------

    public function trash()
    {
        $products = Product::onlyTrashed()
            ->with('category')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('products.trash', compact('products'));
    }

    public function restore($id)
    {
        Product::onlyTrashed()->findOrFail($id)->restore();

        ProductCacheService::bumpVersion();

        return redirect()
            ->route('products.trash')
            ->with('success', 'Product Restored Successfully');
    }

    public function forceDelete($id)
    {
        Product::onlyTrashed()->findOrFail($id)->forceDelete();

        ProductCacheService::bumpVersion();

        return redirect()
            ->route('products.trash')
            ->with('success', 'Product Permanently Deleted');
    }

    // ---------- Bulk CSV Import ----------

    public function import()
    {
        return view('products.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();

        // Strip UTF-8 BOM so the header keys stay correct
        $content = preg_replace('/^\xEF\xBB\xBF/', '', (string) file_get_contents($path));

        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        $header = array_map('trim', (array) fgetcsv($handle));
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) !== count($header)) {
                continue;
            }
            $rows[] = array_combine($header, $data);
        }

        fclose($handle);

        $imported = 0;

        foreach ($rows as $row) {
            $validator = Validator::make($row, [
                'name' => 'required',
                'price' => 'required|numeric',
                'category' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                continue;
            }

            $categoryId = null;

            if (! empty($row['category'])) {
                $category = Category::firstOrCreate(['name' => trim($row['category'])]);
                $categoryId = $category->id;
            }

            Product::create([
                'name' => $row['name'],
                'price' => $row['price'],
                'category_id' => $categoryId,
            ]);

            $imported++;
        }

        ProductCacheService::bumpVersion();

        return redirect()
            ->route('products.index')
            ->with('success', "Imported {$imported} products successfully");
    }

    // Clear Cache (bumps version -> global invalidation)
    public function clearCache()
    {
        ProductCacheService::bumpVersion();
        Cache::flush();

        return redirect()
            ->route('products.index')
            ->with('success', 'Cache Cleared Successfully');
    }

    public function cacheKeys()
    {
        $keys = CacheKeyService::all();

        return view('products.cache-keys', compact('keys'));
    }

    public function exportCSV()
    {
        $fileName = 'products.csv';
        $products = Product::with('category')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Price', 'Category']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->price,
                    $product->category?->name,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cacheStatus()
    {
        $data = [
            'cache_driver' => config('cache.default'),
            'cache_ttl' => ProductCacheService::getTtl(),
            'cache_version' => ProductCacheService::version(),
            'taggable' => ProductCacheService::isTaggable(),
            'cached_items' => Cache::getStore() instanceof \Illuminate\Cache\TaggableStore ? 'Taggable Cache' : 'Versioned Cache',
        ];

        return view('products.cache-status', compact('data'));
    }

    public function warmUpCache()
    {
        $this->buildQuery(request())->get();

        return redirect()
            ->route('products.index')
            ->with('success', 'Cache Warmed Up Successfully');
    }

    public function analytics()
    {
        $hits = CacheLog::where('type', 'HIT')->count();
        $misses = CacheLog::where('type', 'MISS')->count();
        $total = $hits + $misses;

        $hitRate = $total > 0 ? round(($hits / $total) * 100, 2) : 0;
        $missRate = $total > 0 ? round(($misses / $total) * 100, 2) : 0;

        $avgDuration = (float) CacheLog::where('type', 'MISS')
            ->whereNotNull('duration')
            ->avg('duration');

        $slowQueries = CacheLog::where('type', 'MISS')
            ->whereNotNull('duration')
            ->orderByDesc('duration')
            ->limit(5)
            ->get();

        return view('products.analytics', compact(
            'hits', 'misses', 'total', 'hitRate', 'missRate', 'avgDuration', 'slowQueries'
        ));
    }
}
