<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }
}
