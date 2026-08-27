<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->withCount('products')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        Category::create($request->only('name'));

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category Added Successfully');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category Deleted Successfully');
    }
}
