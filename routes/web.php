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
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Product;
use App\Models\News;
use App\Models\Sitemap;


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
Route::post('/product/comment', [ProductCommentController::class, 'store'])->name('product.comment');


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
    Route::post('product/{id}/toggle-popular', [ProductController::class, 'togglePopular'])->name('product.toggle-popular');

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

    Route::post('admin-news/{id}/toggle-featured', [NewsController::class, 'toggleFeatured'])->name('admin-news.toggle-featured');

    // Sitemap Management
    Route::get('/sitemap-list', [SitemapController::class, 'index'])->name('sitemap.list');
    Route::post('/sitemap-store', [SitemapController::class, 'store'])->name('sitemap.store');
    Route::get('/sitemap-delete/{id}', [SitemapController::class, 'destroy'])->name('sitemap.delete');
    Route::post('/sitemap-toggle/{id}', [SitemapController::class, 'toggle'])->name('sitemap.toggle');

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

// Dynamic Sitemap
Route::get('/sitemap.xml', function () {
    
    $urls = [];
    $baseUrl = config('app.url', 'http://localhost');

    // Static pages (always included)
    $now = now()->toDateString();
    $urls[] = ['loc' => $baseUrl . '/', 'lastmod' => $now, 'priority' => '1.0', 'changefreq' => 'daily'];
    $urls[] = ['loc' => $baseUrl . '/store', 'lastmod' => $now, 'priority' => '0.9', 'changefreq' => 'daily'];
    $urls[] = ['loc' => $baseUrl . '/news', 'lastmod' => $now, 'priority' => '0.8', 'changefreq' => 'daily'];
    $urls[] = ['loc' => $baseUrl . '/about', 'lastmod' => $now, 'priority' => '0.6', 'changefreq' => 'monthly'];
    $urls[] = ['loc' => $baseUrl . '/contact-us', 'lastmod' => $now, 'priority' => '0.6', 'changefreq' => 'monthly'];

    // Products (only if is_sitemap = 1)
    Product::select('slug', 'updated_at', 'status')
        ->where('status', 'launched')
        ->where('is_sitemap', 1)
        ->orderBy('updated_at', 'desc')
        ->chunk(100, function ($products) use (&$urls, $baseUrl) {
            foreach ($products as $product) {
                $urls[] = [
                    'loc' => $baseUrl . '/book-details/' . $product->slug,
                    'lastmod' => $product->updated_at ? $product->updated_at->toIso8601String() : now()->toIso8601String(),
                    'priority' => '0.8',
                    'changefreq' => 'weekly',
                ];
            }
        });

    // News articles (only if is_sitemap = 1)
    News::select('slug', 'updated_at', 'publish_date')
        ->where('status', 'published')
        ->where('is_sitemap', 1)
        ->orderBy('publish_date', 'desc')
        ->chunk(100, function ($news) use (&$urls, $baseUrl) {
            foreach ($news as $item) {
                $urls[] = [
                    'loc' => $baseUrl . '/news/' . $item->slug,
                    'lastmod' => ($item->updated_at ?? $item->publish_date)->toIso8601String(),
                    'priority' => '0.7',
                    'changefreq' => 'weekly',
                ];
            }
        });

    // Custom URLs from sitemaps table
    Sitemap::where('is_active', true)
        ->chunk(100, function ($sitemaps) use (&$urls) {
            foreach ($sitemaps as $sitemap) {
                $urls[] = [
                    'loc' => $sitemap->url,
                    'priority' => $sitemap->priority,
                    'changefreq' => $sitemap->changefreq,
                ];
            }
        });

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
        if (!empty($url['lastmod'])) {
            $xml .= "    <lastmod>" . e($url['lastmod']) . "</lastmod>\n";
        }
        $xml .= "    <changefreq>" . e($url['changefreq'] ?? 'monthly') . "</changefreq>\n";
        $xml .= "    <priority>" . e($url['priority'] ?? '0.5') . "</priority>\n";
        $xml .= "  </url>\n";
    }
    $xml .= '</urlset>';

    return response($xml, 200)
        ->header('Content-Type', 'application/xml')
        ->header('Cache-Control', 'public, max-age=3600');
});


require __DIR__.'/auth.php';