<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Testimonial;
use App\Models\ProductCategory;
use App\Models\Cart;

use App\Models\ProductComment;
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
        
        $metaTitle = 'Store - Speech Publications';
        $metaDescription = 'Browse our collection of books at Speech Publications. Find the best titles across various genres.';
        $metaImage = asset('images/logo.png');
        
        return view('store.shop', compact('categories', 'products', 'metaTitle', 'metaDescription', 'metaImage'));
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

        $images = json_decode($productData->image, true) ?? [];
        $metaTitle = $productData->name . ' - Speech Publications';
        $metaDescription = Str::limit(strip_tags($productData->description ?? $productData->heading ?? $productData->name), 160);
        $metaImage = !empty($images) && isset($images[0]) ? Storage::disk('s3')->url('product/'.$images[0]) : asset('images/logo.png');

        $comments = ProductComment::where('product_id', $productData->id)->where('status', 'approved')->orderBy('created_at', 'desc')->paginate(10);
        $commentsCount = ProductComment::where('product_id', $productData->id)->where('status', 'approved')->count();
   
        return view('store.shop-details',['product'=>$productData,'relatedProduct'=>$products,'slug'=>$request->slug,'cartItems'=>$cartItems, 'metaTitle'=>$metaTitle, 'metaDescription'=>$metaDescription, 'metaImage'=>$metaImage, 'comments'=>$comments, 'commentsCount'=>$commentsCount]);
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