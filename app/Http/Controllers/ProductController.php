<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    // Product Listing + Search + Pagination
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::when($search, function ($query) use ($search) {

            $query->where('name', 'like', "%{$search}%")
                ->orWhere('price', 'like', "%{$search}%");

        })
            ->orderBy('id', 'asc')
            ->paginate(3);

        // Dashboard Stats
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
}