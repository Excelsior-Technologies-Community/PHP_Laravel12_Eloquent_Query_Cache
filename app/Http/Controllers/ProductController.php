<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // 🔥 THIS QUERY IS AUTO CACHED
        $products = Product::orderBy('id', 'desc')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        Product::create($request->only('name', 'price'));

        // 🔥 Cache auto flushed by package
        return redirect()->route('products.index')
            ->with('success', 'Product Added (Cache Auto Cleared)');
    }
}