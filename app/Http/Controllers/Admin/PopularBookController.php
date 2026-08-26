<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class PopularBookController extends Controller
{
    public function index()
    {
        $popularBooks = Product::where('is_popular', true)->orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.popular_books.index', compact('popularBooks', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $product = Product::find($request->product_id);
        $product->is_popular = true;
        $product->save();
        return redirect()->route('admin.popular-books.index')->with('success', 'Product marked as popular.');
    }

    public function destroy(Product $popularBook)
    {
        $popularBook->is_popular = false;
        $popularBook->save();
        return redirect()->route('admin.popular-books.index')->with('success', 'Removed from popular books.');
    }
}
