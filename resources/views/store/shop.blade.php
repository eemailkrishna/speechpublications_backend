 @include('layouts.store-header')

 <link rel="stylesheet" href="{{ url('public/store/assets/css/store-beauty.css') }}">

 <style>
    .product-title-css {
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    .product-title-css a { color: inherit !important; text-decoration: none !important; }
    @media (min-width: 991px) {
        .product-title-css { min-height: 33px !important; }
    }
 </style>

 <!-- Breadcumb Section Start -->
 <div class="breadcrumb-wrapper bg-cover section-paddingn"
     style="background: var(--sp-card);">
     <div class="container">
         <div class="page-heading">
             <h1>Shop</h1>
             <div class="page-header">
                 <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                     <li>
                         <a href="{{url('/')}}">Home</a>
                     </li>
                     <li>
                         <i class="fa-solid fa-chevron-right"></i>
                     </li>
                     <li>Shop</li>
                 </ul>
             </div>
         </div>
     </div>
 </div>

 <!-- Category Chip Rail -->
 <div class="sp-chip-rail">
     <div class="container">
         <a href="{{ route('store.index') }}" class="sp-chip {{ !request('category') ? 'active' : '' }}">All Products</a>
         @foreach($categories as $category)
         <a href="{{ route('store.index', ['category' => $category->id]) }}" class="sp-chip {{ request('category') == $category->id ? 'active' : '' }}">
             {{ $category->name }}
         </a>
         @endforeach
     </div>
 </div>

 <!-- Shop Section Start -->
 <section class="shop-section fix section-padding">
     <div class="container">
         <div class="shop-default-wrapper">
             <div class="row">

                 <!-- Results bar -->
                 <div class="col-12">
                     <div class="woocommerce-notices-wrapper wow fadeInUp" data-wow-delay=".3s">
                         <p>Showing {{ $products->count() > 0 ? ((($products->currentPage() - 1) * $products->perPage()) + 1) : 0 }}–{{ min($products->currentPage() * $products->perPage(), $products->total()) }} of {{ $products->total() }} results</p>
                     </div>
                 </div>

                 <!-- Sidebar (desktop) -->
                 <div class="col-xl-3 col-lg-4 order-md-1 wow fadeInUp" data-wow-delay=".3s">
                     <div class="main-sidebar">
                         <div class="single-sidebar-widget">
                             <div class="wid-title">
                                 <h5>Search</h5>
                             </div>
                             <form action="{{ route('store.index') }}" method="GET" class="search-toggle-box">
                                 <div class="input-area search-container">
                                     <input class="search-input text-black" type="text" name="search" placeholder="Search books..." value="{{ request('search') }}">
                                     <button type="submit" class="cmn-btn search-icon">
                                         <i class="far fa-search"></i>
                                     </button>
                                 </div>
                             </form>
                         </div>
                         <div class="single-sidebar-widget">
                             <div class="wid-title">
                                 <h5>Categories</h5>
                             </div>
                             <div class="categories-list">
                                 <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                     <li class="nav-item" role="presentation">
                                         <a class="nav-link {{ !request('category') ? 'active' : '' }}"
                                            href="{{ route('store.index') }}" role="tab">
                                             All Products
                                         </a>
                                     </li>
                                     @foreach($categories as $category)
                                     <li class="nav-item" role="presentation">
                                         <a class="nav-link {{ request('category') == $category->id ? 'active' : '' }}"
                                            href="{{ route('store.index', ['category' => $category->id]) }}" role="tab">
                                             {{ $category->name }}
                                         </a>
                                     </li>
                                     @endforeach
                                 </ul>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- Products grid -->
                 <div class="col-xl-9 col-lg-8 col-12 order-md-2">
                     <div class="tab-content" id="pills-tabContent">
                         <div class="tab-pane fade show active" id="pills-arts" role="tabpanel"
                             aria-labelledby="pills-arts-tab" tabindex="0">
                             <div class="row" style="align-items: stretch;">

                             @if($products->isEmpty())
                                 <div class="col-12 text-center" style="padding:60px 20px;">
                                     <i class="fas fa-book-open" style="font-size:48px; color:var(--sp-muted); opacity:.3; margin-bottom:16px; display:block;"></i>
                                     <h4 style="color:var(--sp-muted); font-weight:600;">No products found.</h4>
                                     <a href="{{ route('store.index') }}" style="color:var(--sp-maroon); font-weight:600; margin-top:8px; display:inline-block;">Clear filters</a>
                                 </div>
                             @else
                                 @foreach($products as $product)
                                 @php
                                     $images = json_decode($product->image, true) ?? [];
                                     $imageUrl = !empty($images) && isset($images[0])
                                         ? Storage::disk('s3')->url('product/'.$images[0])
                                         : asset('images/no-image.png');
                                 @endphp
                                 <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 wow fadeInUp" data-wow-delay=".2s" style="margin-bottom: 20px; margin-top: 10px; display: flex;">
                                      <div class="shop-box-items">
                                          <div class="book-thumb center" style="position:relative;">
                                             <a href="{{ url('/book-details/'.$product->slug) }}">
                                                 <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                             </a>

                                             @if($product->category)
                                             <span class="sp-ribbon">{{ $product->category->name }}</span>
                                             @endif

                                             @if($product->status == 'coming-soon')
                                                 <div class="sp-coming-soon-overlay">
                                                     <span>Coming Soon</span>
                                                 </div>
                                             @endif

                                             @if($product->is_ebook == 1 && $product->status != 'coming-soon')
                                             <span class="badge badge-success" style="position:absolute; left:12px; bottom:12px;">
                                                 E-Book
                                             </span>
                                             @endif
                                         </div>
                                         <div class="shop-content">
                                             <h3 class="product-title-css">
                                                 <a href="{{url('/book-details/'.$product->slug)}}">{{ $product->name }}</a>
                                             </h3>
                                              @if($product->author_name)
                                              <p class="product-title-css" style="color:var(--sp-muted); font-size:12px; margin:0 0 6px;">
                                                  {{ $product->author_name }}
                                              </p>
                                              @endif
                                              @if($product->rating)
                                              <div style="margin-bottom: 6px; display: flex; align-items: center; gap: 4px;">
                                                  @for($i = 1; $i <= 5; $i++)
                                                      @if($i <= $product->rating)
                                                          <i class="fa-solid fa-star" style="font-size: 12px; color: #c6862e;"></i>
                                                      @else
                                                          <i class="fa-regular fa-star" style="font-size: 12px; color: #c6862e;"></i>
                                                      @endif
                                                  @endfor
                                                  <span style="font-size: 11px; color: var(--sp-muted); margin-left: 2px;">({{ $product->rating }})</span>
                                              </div>
                                              @endif
                                             <ul class="price-list1" @if($product->is_ebook != 1) style="margin-top: 6px;" @endif>
                                                 <li>₹{{ $product->price }}/-</li>
                                                 @if($product->is_ebook == 1)
                                                     <li style="font-size:12px !important; color:var(--sp-muted) !important; font-weight:500 !important;">E-Book: ₹{{ $product->ebook_price }}/-</li>
                                                 @endif
                                             </ul>
                                             @if($product->status != 'coming-soon')
                                             <div class="shop-button" style="padding-bottom: 0;">
                                                 <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display:inline;">
                                                     @csrf
                                                     <input type="hidden" name="quantity" value="1">
                                                     <button type="submit" class="theme-btn">Add To Cart</button>
                                                 </form>
                                             </div>
                                             @else
                                             <div class="shop-button" style="padding-bottom: 0;">
                                                 <button type="button" class="theme-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Coming Soon</button>
                                             </div>
                                             @endif
                                         </div>
                                     </div>
                                 </div>
                                 @endforeach
                             @endif

                             </div>
                         </div>
                     </div>
                     <div class="page-nav-wrap text-center">
                         {{ $products->links('pagination::bootstrap-4') }}
                     </div>
                 </div>

             </div>
         </div>
     </div>
 </section>

 <!-- Mobile Filter FAB -->
 <button class="sp-filter-fab" id="sp-filter-fab">
     <i class="fas fa-sliders-h"></i> Filter & Sort
 </button>

 <!-- Mobile Filter Drawer -->
 <div class="sp-filter-drawer-overlay" id="sp-filter-overlay"></div>
 <div class="sp-filter-drawer" id="sp-filter-drawer">
     <div class="sp-filter-drawer-header">
         <h3>Filter & Sort</h3>
         <button class="sp-filter-drawer-close" id="sp-filter-close"><i class="fas fa-times"></i></button>
     </div>
     <div style="margin-bottom:16px;">
         <form action="{{ route('store.index') }}" method="GET">
             <div class="input-area search-container" style="position:relative;">
                 <input class="search-input" type="text" name="search" placeholder="Search books..." value="{{ request('search') }}" style="width:100%; padding:12px 50px 12px 14px; border:1px solid var(--sp-line); border-radius:10px; font-size:13px; font-family:var(--font-ui);">
                 <button type="submit" style="position:absolute; right:6px; top:50%; transform:translateY(-50%); width:38px; height:38px; border-radius:50%; border:none; background:var(--sp-maroon); color:#fff; cursor:pointer;"><i class="fas fa-search"></i></button>
             </div>
         </form>
     </div>
     <div style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:var(--sp-muted); margin-bottom:12px; font-family:var(--font-ui);">Categories</div>
     <div style="display:flex; flex-direction:column; gap:2px;">
         <a href="{{ route('store.index') }}" style="display:block; padding:8px 12px; border-radius:6px; font-size:13.5px; font-weight:500; color:{{ !request('category') ? 'var(--sp-maroon)' : 'var(--sp-ink)' }}; {{ !request('category') ? 'font-weight:700;' : '' }} text-decoration:none;">All Products</a>
         @foreach($categories as $category)
         <a href="{{ route('store.index', ['category' => $category->id]) }}" style="display:block; padding:8px 12px; border-radius:6px; font-size:13.5px; font-weight:500; color:{{ request('category') == $category->id ? 'var(--sp-maroon)' : 'var(--sp-ink)' }}; {{ request('category') == $category->id ? 'font-weight:700;' : '' }} text-decoration:none;">{{ $category->name }}</a>
         @endforeach
     </div>
 </div>

 <script>
 document.addEventListener('DOMContentLoaded', function() {
     var fab = document.getElementById('sp-filter-fab');
     var overlay = document.getElementById('sp-filter-overlay');
     var drawer = document.getElementById('sp-filter-drawer');
     var closeBtn = document.getElementById('sp-filter-close');
     function openDrawer() { overlay.classList.add('open'); drawer.classList.add('open'); }
     function closeDrawer() { overlay.classList.remove('open'); drawer.classList.remove('open'); }
     if (fab) fab.addEventListener('click', openDrawer);
     if (overlay) overlay.addEventListener('click', closeDrawer);
     if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
 });
 </script>

 @include('layouts.store-footer')
