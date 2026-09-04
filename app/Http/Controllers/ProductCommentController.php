<?php

namespace App\Http\Controllers;

use App\Models\ProductComment;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        ProductComment::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'comment' => $request->comment,
            'rating' => $request->rating ?? 5,
            'status' => 'approved',
        ]);

        return back()->with('success', 'Comment posted successfully!');
    }
}
