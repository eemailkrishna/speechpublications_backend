<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsAuthorController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsFrontController;
use App\Http\Middleware\AdminMiddleware;


// Route::get('/', function () {
//     return view('home');
// });
Route::get('/', [HomeController::class, 'index']);
Route::get('/news', [HomeController::class, 'news'])->name('news.index');
Route::get('/news/load-more', [NewsFrontController::class, 'loadMore'])->name('news.load-more');
Route::get('/news/author/{slug}', [NewsFrontController::class, 'author'])->name('news.author');
Route::get('/news/category/{slug}', [NewsFrontController::class, 'category'])->name('news.category');
Route::get('/news/{slug}', [NewsFrontController::class, 'show'])->name('news.details');
Route::post('/news/comment', [NewsFrontController::class, 'comment'])->name('news.comment');


Route::get('/contact-us', function () {
    return view('contact-us');
});
Route::get('/about', function () {
    return view('about');
});

Route::get('/dashboard1', function () {
    return view('admin.dashboard.index');
});


Route::get('/store', [StoreController::class, 'index'])->name('store.index');
// Route::get('/store', [StoreController::class, 'index']);
Route::get('/book-details/{slug}', [StoreController::class, 'book_details']);
Route::get('/cart', [CartController::class, 'viewCart']);
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');

Route::post('/cart/update/{cartId}', [CartController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove/{cartId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');

// Checkout Routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process-cod', [CheckoutController::class, 'processCheckoutCOD'])->name('checkout.process.cod');
    Route::post('/checkout/create-razorpay-order', [CheckoutController::class, 'createRazorpayOrder'])->name('checkout.razorpay.create');
    Route::post('/checkout/verify-razorpay-payment', [CheckoutController::class, 'verifyRazorpayPayment'])->name('checkout.razorpay.verify');
    Route::get('/order-confirmation/{orderId}', [CheckoutController::class, 'orderConfirmation'])->name('order.confirmation');
});


Route::post('/contact-us', [ProfileController::class, 'submitContact'])->name('contact.submit');


Route::get('/dashboard', [ProductController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    
    Route::get('order-history', [ProductController::class, 'OrderHistory'])->name('order.history');
    Route::get('order-view/{id}', [ProductController::class, 'OrderView'])->name('order.view');
    Route::get('user-order-history', [ProductController::class, 'UserOrderHistory'])->name('user.order.history');
    Route::get('download-ebook/{orderId}/{itemId}', [ProductController::class, 'downloadEbook'])->name('download.ebook');
    Route::get('view-ebook/{orderId}/{itemId}', [ProductController::class, 'viewEbook'])->name('view.ebook');
    Route::get('read-ebook/{orderId}/{itemId}', [ProductController::class, 'readEbook'])->name('read.ebook');
    
   

    
});




Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/testimonial-list',[TestimonialController::class,'index'])->name('testimonial.list');
    Route::get('/testimonial-add',[TestimonialController::class,'testimonial_add']);
    Route::post('/testimonial/store', [TestimonialController::class, 'store'])->name('testimonial.store');

    Route::get('/testimonial/edit/{id}', [TestimonialController::class, 'edit'])->name('testimonial.edit');
    Route::post('/testimonial/update/{id}', [TestimonialController::class, 'update'])->name('testimonial.update');
    Route::delete('/testimonial/delete/{id}', [TestimonialController::class, 'destroy'])->name('testimonial.delete');
    
     Route::get('/banner-list', [BannerController::class, 'index']);
    Route::get('/banner-create', [BannerController::class, 'create']);
    Route::get('/banner-edit/{id}', [BannerController::class, 'edit']);
    Route::post('banner/{id}', [BannerController::class, 'update'])->name('banner.update');
    
    
    Route::get('/product-category-list', [ProductCategoryController::class, 'show']);
    Route::post('/product-category-store', [ProductCategoryController::class, 'store'])->name('product-category.store');
    Route::get('/product-category-create', [ProductCategoryController::class, 'index']);
    Route::get('/product-category-delete/{id}', [ProductCategoryController::class, 'delete_category']);
    Route::get('/product-category-edit/{id}', [ProductCategoryController::class, 'edit']);
    Route::post('product-category-update/{id}', [ProductCategoryController::class, 'update'])->name('product-category.update');
    Route::post('/update-category-order', [ProductCategoryController::class, 'updateOrder']);
    
       
    Route::get('/product-list', [ProductController::class, 'index']);
     Route::post('/product-create', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product-create', [ProductController::class, 'create']);
    Route::get('/product-edit/{id}', [ProductController::class, 'edit']);
    Route::get('/product-delete/{id}', [ProductController::class, 'delete']);
    Route::post('products/{id}', [ProductController::class, 'update'])->name('products.update');

    // News Management Routes
    Route::post('/admin-news/upload-image', [NewsController::class, 'uploadEditorImage'])
        ->name('admin-news.upload-image');

    Route::resource('admin-news', NewsController::class, [
        'parameters' => ['admin-news' => 'news']
    ])->names([
        'index' => 'admin-news.index',
        'create' => 'admin-news.create',
        'store' => 'admin-news.store',
        'edit' => 'admin-news.edit',
        'update' => 'admin-news.update',
        'destroy' => 'admin-news.destroy',
    ]);

    Route::resource('admin-news-author', NewsAuthorController::class, [
        'parameters' => ['admin-news-author' => 'author']
    ])->names([
        'index' => 'admin-news-author.index',
        'create' => 'admin-news-author.create',
        'store' => 'admin-news-author.store',
        'edit' => 'admin-news-author.edit',
        'update' => 'admin-news-author.update',
        'destroy' => 'admin-news-author.destroy',
    ]);

    Route::resource('admin-news-category', NewsCategoryController::class, [
        'parameters' => ['admin-news-category' => 'category']
    ])->names([
        'index' => 'admin-news-category.index',
        'create' => 'admin-news-category.create',
        'store' => 'admin-news-category.store',
        'edit' => 'admin-news-category.edit',
        'update' => 'admin-news-category.update',
        'destroy' => 'admin-news-category.destroy',
    ]);

    
});

Route::middleware(['auth'])->group(function () {
    Route::post('/order/update-status', [ProductController::class, 'updateOrderStatus'])
        ->name('order.update-status');
});


require __DIR__.'/auth.php';