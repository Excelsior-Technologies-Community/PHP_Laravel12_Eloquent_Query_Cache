<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CacheLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // Product Listing + Search + Pagination
    public function index(Request $request)
    {
        $search = $request->search;
        $page = $request->get('page', 1);

        $cacheKey = "products_" . md5($search . "_" . $page);

        if (Cache::has($cacheKey)) {

            // CACHE HIT
            CacheLog::create([
                'type' => 'HIT',
                'query' => $search
            ]);

            $products = Cache::get($cacheKey);
        } else {

            // CACHE MISS
            $products = Product::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%");
            })
                ->orderBy('id', 'asc')
                ->paginate(3);

            Cache::put($cacheKey, $products, 10);

            CacheLog::create([
                'type' => 'MISS',
                'query' => $search
            ]);
        }

        $totalProducts = Product::count();
        $totalPrice = Product::sum('price');

        return view('products.index', compact(
            'products',
            'search',
            'totalProducts',
            'totalPrice'
        ));
    }

    // Create Page
    public function create()
    {
        return view('products.create');
    }

    // Store Product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        Product::create($request->only('name', 'price'));

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Added Successfully');
    }

    // Edit Page
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('products.edit', compact('product'));
    }

    // Update Product
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $product = Product::findOrFail($id);

        $product->update($request->only('name', 'price'));

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Updated Successfully');
    }

    // Delete Product
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product Deleted Successfully');
    }

    // Clear Cache
    public function clearCache()
    {
        Cache::flush();

        return redirect()
            ->route('products.index')
            ->with('success', 'Cache Cleared Successfully');
    }

    public function exportCSV()
    {
        $fileName = 'products.csv';
        $products = Product::all();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Price']);

            foreach ($products as $product) {
                fputcsv($file, [
                    $product->id,
                    $product->name,
                    $product->price
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cacheStatus()
    {
        return response()->json([
            'cache_driver' => config('cache.default'),
            'cached_items' => Cache::getStore() instanceof \Illuminate\Cache\TaggableStore ? 'Taggable Cache' : 'File Cache',
        ]);
    }

    public function warmUpCache()
    {
        Product::orderBy('id', 'desc')->get();

        return redirect()
            ->route('products.index')
            ->with('success', 'Cache Warmed Up Successfully');
    }

    public function analytics()
    {
        $hits = CacheLog::where('type', 'HIT')->count();
        $misses = CacheLog::where('type', 'MISS')->count();

        return view('products.analytics', compact('hits', 'misses'));
    }
}
