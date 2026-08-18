<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Testimonial;
use App\Models\ProductCategory;
use App\Models\Cart;

use App\Models\Product;
use Mail;
use validated;


class StoreController extends Controller
{
    public function index(Request $request){
        $categories = ProductCategory::all();
        
       
        $query = Product::query();
        
        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by search term if provided
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                   ->orWhere('author_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('heading', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Order by latest and paginate (20 per page)
        $products = $query->orderBy('id', 'desc')->paginate(20);
        
        return view('store.shop', compact('categories', 'products'));
    }
    public function book_details(Request $request){
    
        $product=Product::where('slug',$request->slug)->first();
        
        $category1= Product::where('slug',$request->slug)->first();
        
        $products=Product::OrderBy('id','desc')->where('category_id',@$category1->category_id)->take(8)->get();
        $productData = Product::where('slug',$request->slug)->first();


        
        $user = Auth::user();
        
        if ($user) {
            $cartItems = Cart::with('product')->where('user_id', $user->id)->where('product_id', $product->id)->first();
        } else {
            $cartItems = $this->getSessionCartItems();
        }
        // return $cartItems; 
   
        return view('store.shop-details',['product'=>$productData,'relatedProduct'=>$products,'slug'=>$request->slug,'cartItems'=>$cartItems]);
        // return $product;
        //  return view('store.shop-details',compact('product'));
    }
    
    public function cart(){
         return view('store.cart');
    }
    public function checkout(){
         return view('store.checkout');
    }
      private function getSessionCartItems()
    {
        return session()->get('cart', []);
    }

}