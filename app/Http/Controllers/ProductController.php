<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\ProductCategory;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $allProduct= Product::orderBy('created_at','desc')->paginate(120);
       
        return view('admin.product.list',['products'=>$allProduct]);
    }
    
    public function create()
    {
        $productCategories= ProductCategory::all();
        return view('admin.product.create',['productCategories'=>$productCategories]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'ebook_price' => 'nullable|numeric|min:0',
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pdf_file' => 'nullable|mimes:pdf|max:10240', // 10MB max for PDF
        ]);
        $uploadedImages = [];
        if($request->hasFile('image')) {
           foreach ($request->file('image') as $file) {
                $fileName = uniqid('', true) . '.' . $file->getClientOriginalExtension();
                Storage::disk('s3')->putFileAs(
                    'product',   // folder name in S3
                    $file,
                    $fileName
                );
                $uploadedImages[] = $fileName;
            }
        }
        $uploadedEbook = [];
        if($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = uniqid('', true) . '.' . $file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'ebook',   // folder name in S3
                $file,
                $fileName
            );
            $uploadedEbook[] = $fileName;
        }
        $slug = Str::slug($request->name, '-');
        Product::create([
            'slug' => $slug,
            'name' => $request->name,
            'author_name' => $request->author_name,
            'rating' => $request->rating,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'description' =>$request->description,
           
            'price' => $request->price,
            'ebook_price' => $request->ebook_price,
            'image' => json_encode($uploadedImages),
            'ebook_pdf' => json_encode($uploadedEbook),
            'is_ebook' => $request->has('is_ebook') ? 1 : 0,
            'heading' => $request->heading,
            'specification' => $request->input('specifications'),
            'inside_the_box' => $request->input('box_contents'),
            'status' => $request->status,
        ]);
        return back()->with('success', 'Product added successfully!');
    }
    
    public function product_detail(Request $request){
        
        $category1= Product::where('slug',$request->slug)->first();
        
        $products=Product::OrderBy('id','desc')->where('category_id',@$category1->category_id)->take(8)->get();
        $productData = Product::where('slug',$request->slug)->first();
        return view('product-detail',['product_details'=>$productData,'relatedProduct'=>$products,'slug'=>$request->slug]);
    }
    
    public function edit($id)
    {
        $productCategories= ProductCategory::all();
        $product = Product::findOrFail($id);
        return view('admin.product.edit',['product'=>$product,'productCategories'=>$productCategories]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'ebook_price' => 'nullable|numeric|min:0',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow multiple images
            'pdf_file' => 'nullable|mimes:pdf', // 10MB max for PDF
        ]);
        
        $product = Product::findOrFail($id);
        
        // if ($request->hasFile('image')) {
        //     $file=public_path('images/product/' . $product->image);
        //     if($product->image){
        //         if (file_exists($file)) {
        //             if (unlink($file)) {
        //                 echo 'File deleted successfully.';
        //             } else {
        //                 echo 'Failed to delete file.';
        //             }
        //         } else {
        //             echo 'File does not exist.';
        //         }
        //     }
        //     $file = $request->file('image');
        //     $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        //     $file->move(public_path('images/product/'), $filename);
        //     $product->image = $filename;
        // }


        
        $currentImages =  json_decode($product->image, true) ?? []; // Decode current images
        if ($request->input('remove_images')) {
            $imagesToRemove = $request->input('remove_images'); // Array of image names to remove

            // Remove from current images array
            $currentImages = array_values(array_diff($currentImages, $imagesToRemove));

            // Delete the removed files from S3
            foreach ($imagesToRemove as $oldFile) {
                $oldPath = 'product/' . $oldFile;
                if (Storage::disk('s3')->exists($oldPath)) {
                    Storage::disk('s3')->delete($oldPath);
                }
            }
        }

        if ($request->hasFile('image')) {
            if ($request->hasFile('image')) {

            foreach ($request->file('image') as $file) {
                $fileName = uniqid('', true) . '.' . $file->getClientOriginalExtension();
                Storage::disk('s3')->putFileAs(
                    'product',
                    $file,
                    $fileName
                );
                // sirf filename save
                $currentImages[] = $fileName;
            }
        }
        }

        // Handle PDF upload for ebook (update)
        $uploadedEbook = json_decode($product->ebook_pdf, true) ?? [];
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = uniqid('', true) . '.' . $file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'ebook',
                $file,
                $fileName
            );
            $uploadedEbook[] = $fileName;

            // Optionally delete previous ebook files if you want to replace them
            // (leave existing files if you prefer versioning)
        }

        
        
        $slug = Str::slug($request->name, '-');
       
        // $sanitized_input = htmlspecialchars($request->input('specifications'), ENT_QUOTES, 'UTF-8');
        $decoded_input = htmlspecialchars_decode($request->input('specifications'), ENT_QUOTES);
        
        $product->update([
            'slug' => $slug,
            
            'category_id' => $request->category_id,
            'name' => $request->name,
            'author_name' => $request->author_name,
            'rating' => $request->rating,
            'description' => $request->description,
            'price' => $request->price,
            'ebook_price' => $request->ebook_price,
            'image' => json_encode(array_values($currentImages)), // Store updated images
            'ebook_pdf' => json_encode(array_values($uploadedEbook)),
            'is_ebook' => $request->has('is_ebook') ? 1 : 0,
            'heading' => $request->heading,
            'specification' => $decoded_input,
            'inside_the_box' => $request->input('box_contents'),
            'status' => $request->status,
        ]);
        return back()->with('success', 'Product updated successfully!');
    }
    
    public function show_product($id){
        $category1= productCategory::where('slug',$id)->first();
        $id=$category1->id;
        
        $products=Product::where('category_id',$id)->get();
        
        return view('all-product-list',['products'=>$products]);
    }

    public function delete($id){
       $is_delete= Product::where('id',$id)->delete();
        if($is_delete){
            return back()->with('success','Product deleted successfully');
        }
    }

    public function togglePopular($id){
        $product = Product::findOrFail($id);
        $product->is_popular = !$product->is_popular;
        $product->save();
        return back()->with('success', 'Product popular status updated');
    }

    public function OrderHistory(Request $request){
        $userId = null;
        if(Auth::user()->hasRole('admin')){
            $userId  = $request->query('order'); 
        }else{
            $userId = auth()->id();
        }
        $orders = Order::with(['items.product'])   // 👈 eager loading
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.product.order-history',['orders'=>$orders]);
    }

    public function OrderView($id){
       
       
      $order = Order::with(['items.product'])   // 👈 eager loading
        ->where('id', $id)
        ->first();
        // return $order;
        return view('admin.product.order-view',['order'=>$order]);
    }


       public function dashboard(){
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $allUser = User::all()->count();
                $latestUsers = User::latest()
                    ->take(10)
                    ->get();
                $totalUsers = User::count();
                $todayBookings = Order::whereDate('created_at', Carbon::today())
                    ->count();
                $currentMonthBookings = Order::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count();
                
                return view('admin.dashboard.index', compact(
                'latestUsers',
                'allUser',
                'totalUsers',
                'todayBookings',
                'currentMonthBookings'
            ));
            }
       elseif(Auth::user()->hasRole('user')){
        
            $userId = auth()->id();
            $orders = Order::with(['items.product'])   // 👈 eager loading
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10) 
            ->get();
             return view('admin.dashboard.index',['orders'=>$orders]);
       }
       else{
        return '<h1>You do not have access</h1>';
       }
    
    }

     public function UserOrderHistory(){
       $users = User::role('user')          // 👈 sirf role = user
        ->whereHas('orders')             // 👈 jinke orders hain
        ->withCount('orders')            // 👈 orders count (optional)
        ->latest()
        ->get();
    return view('admin.product.user-order-history', [
        'users' => $users
    ]);
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:pending,processing,delivered,cancelled,shipped',
        ]);

        try {
            $order = Order::find($request->order_id);
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated to ' . ucfirst($request->status)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadEbook($orderId, $itemId)
    {
        // Downloading is disabled. Redirect user to read-only view.
        return redirect()->route('read.ebook', ['orderId' => $orderId, 'itemId' => $itemId]);
    }

    public function viewEbook(Request $request, $orderId, $itemId)
    {
        // Allow direct access; we validate ownership and payment below.
        try {
            // Get the order and verify it belongs to the current user
            $order = Order::findOrFail($orderId);
            
            // Verify payment is completed
            if (!$order->razorpay_payment_id) {
                return redirect()->back()->with('error', 'Payment not verified for this order');
            }

            // Get the order item
            $item = $order->items()->findOrFail($itemId);
            $product = $item->product;

            // Verify it's an ebook
            if (!$product->is_ebook) {
                return redirect()->back()->with('error', 'This product is not an ebook');
            }

            // Get the PDF filename
            $pdfFiles = json_decode($product->ebook_pdf, true);
            if (empty($pdfFiles) || !isset($pdfFiles[0])) {
                return redirect()->back()->with('error', 'PDF file not found');
            }

            $pdfFileName = $pdfFiles[0];
            $path = 'ebook/' . $pdfFileName;

            // Check if file exists on S3
            if (!Storage::disk('s3')->exists($path)) {
                return redirect()->back()->with('error', 'File not found on server');
            }

            // Stream the file content and display inline
            try {
                $stream = Storage::disk('s3')->get($path);
                return response($stream)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $product->name) . '.pdf"');
            } catch (\Throwable $e) {
                return redirect()->back()->with('error', 'Error streaming file: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error viewing file: ' . $e->getMessage());
        }
    }

    public function readEbook($orderId, $itemId)
    {
        try {
            // Get the order and verify it belongs to the current user
            $order = Order::findOrFail($orderId);
            
            // Verify payment is completed
            if (!$order->razorpay_payment_id) {
                return redirect()->back()->with('error', 'Payment not verified for this order');
            }

            // Get the order item
            $item = $order->items()->findOrFail($itemId);
            $product = $item->product;

            // Verify it's an ebook
            if (!$product->is_ebook) {
                return redirect()->back()->with('error', 'This product is not an ebook');
            }

            // Generate URL to view raw PDF
            $pdfUrl = route('view.ebook', ['orderId' => $orderId, 'itemId' => $itemId]);
            
            return view('store.read-ebook', [
                'product' => $product,
                'pdfUrl' => $pdfUrl
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error reading ebook: ' . $e->getMessage());
        }
    }
}