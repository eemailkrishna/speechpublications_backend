 @include('layouts.store-header')

 <link rel="stylesheet" href="{{ url('public/store/assets/css/store-beauty.css') }}">

 <style>
        @media (min-width: 991px) {
            .product-title-css{
                min-height: 40px !important;
                overflow: hidden !important;
            }
        }

        /* 2 line heading with ellipsis */
        .product-title-css {
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            font-size: 0.95rem !important;
        }

        .product-title-css a {
            color: inherit !important;
            text-decoration: none !important;
        }

        /* Reduce text sizes */
        .shop-box-items .price-list1 {
            font-size: 11px !important;
        }

        .shop-box-items .price-list1 li {
            font-size: 12px !important;
        }

        .shop-box-items .shop-content p {
            font-size: 12px !important;
        }

        .shop-box-items .theme-btn {
            background: #003366 !important;
            color: #ffffff !important;
            font-size: 12px !important;
            padding: 12px 40px !important;
            border: none !important;
            width: 100% !important;
        }

        /* Mobile: 2 cards per row */
        @media (max-width: 576px) {
            .col-md-6 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }

            /* Mobile specific styles */
            .shop-box-items h3.product-title-css {
                font-size: 12px !important;
            }

            .shop-box-items .price-list1 {
                font-size: 12px !important;
            }

            .shop-box-items .price-list1 li {
                font-size: 12px !important;
            }

            .shop-box-items .shop-content p {
                font-size: 12px !important;
            }

            .shop-box-items .theme-btn {
                font-size: 10px !important;
            }
        }
 </style>


 <!-- Breadcumb Section Start -->
 <div class="breadcrumb-wrapper bg-cover section-padding"
     style="background-image: url(public/store/assets/img/hero/breadcrumb-bg.jpg);">
     <div class="container">
         <div class="page-heading">
             <h1>Shop </h1>
             <div class="page-header">
                 <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                     <li>
                         <a href="{{url('/')}}">
                             Home
                         </a>
                     </li>
                     <li>
                         <i class="fa-solid fa-chevron-right"></i>
                     </li>
                     <li>
                         Shop
                     </li>
                 </ul>
             </div>
         </div>
     </div>
 </div>

 <!-- Shop Section Start -->
 <section class="shop-section fix section-padding">
     <div class="container">
         <div class="shop-default-wrapper">
             <div class="row">
                 <div class="col-12">
                     <div class="woocommerce-notices-wrapper wow fadeInUp" data-wow-delay=".3s">
                         <p>Showing {{ $products->count() > 0 ? ((($products->currentPage() - 1) * $products->perPage()) + 1) : 0 }}-{{ min($products->currentPage() * $products->perPage(), $products->total()) }} Of {{ $products->total() }} Results </p>
                         <div class="form-clt">
                             <div class="nice-select" tabindex="0">
                                 <span class="current">
                                     <!--Default Sorting-->
                                 </span>
                                 <!--<ul class="list">-->
                                 <!--    <li data-value="1" class="option selected focus">-->
                                 <!--        Default sorting-->
                                 <!--    </li>-->
                                 <!--    <li data-value="1" class="option">-->
                                 <!--        Sort by popularity-->
                                 <!--    </li>-->
                                 <!--    <li data-value="1" class="option">-->
                                 <!--        Sort by average rating-->
                                 <!--    </li>-->
                                 <!--    <li data-value="1" class="option">-->
                                 <!--        Sort by latest-->
                                 <!--    </li>-->
                                 <!--</ul>-->
                             </div>
                             <!--<div class="icon">-->
                             <!--    <a href="shop-list.html"><i class="fas fa-list"></i></a>-->
                             <!--</div>-->
                             <!--<div class="icon-2 active">-->
                             <!--    <a href="shop.html"><i class="fa-sharp fa-regular fa-grid-2"></i></a>-->
                             <!--</div>-->
                         </div>
                     </div>
                 </div>
                 <div class="col-xl-3 col-lg-4  order-md-1 wow fadeInUp" data-wow-delay=".3s">
                     <div class="main-sidebar">
                         <div class="single-sidebar-widget">
                             <div class="wid-title">
                                 <h5>Search</h5>
                             </div>
                             <form action="{{ route('store.index') }}" method="GET" class="search-toggle-box">
                                 <div class="input-area search-container">
                                     <input class="search-input text-black" type="text" name="search" placeholder="Search by book or author" value="{{ request('search') }}">
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
                                         <a style="padding: 10px;"  class="nav-link {{ !request('category') ? 'active' : '' }}" 
                                            href="{{ route('store.index') }}" role="tab">
                                             All Products
                                         </a>
                                     </li>
                                     @foreach($categories as $category)
                                     <li class="nav-item" role="presentation">
                                         <a style="padding: 10px;" class="nav-link {{ request('category') == $category->id ? 'active' : '' }}" 
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
                 <div class="col-xl-9 col-lg-8  order-md-2">
                     <div class="tab-content" id="pills-tabContent">
                         <div class="tab-pane fade show active" id="pills-arts" role="tabpanel"
                             aria-labelledby="pills-arts-tab" tabindex="0">
                             <div class="row">

                             @if($products->isEmpty())
                                <div class="col-12 text-center">
                                    <h4 class="text-muted">No products found.</h4>
                                </div>
                             @else  
                                 @foreach($products as $product)
                              

                                 <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6   wow fadeInUp  " data-wow-delay=".2s" style="margin-bottom: 40px; margin-top: 25px;">
                                     <div class="shop-box-items">
                                         <div class="book-thumb center" style="position: relative;height:auto;">
                                                 @php
                                                $images = json_decode($product->image, true) ?? [];
                                                $imageUrl = !empty($images) && isset($images[0])
                                                    ? Storage::disk('s3')->url('product/'.$images[0])
                                                    : asset('images/no-image.png'); // fallback image
                                            @endphp

                                            <a href="{{ url('/book-details/'.$product->slug) }}">
                                                <img  src="{{ $imageUrl }}" alt="img">
                                            </a>
                                            
                                                @if($product->status =='coming-soon')
                                            <span class="badge badge-danger" style="position: absolute; background-color: #dc3545; color: white;">
                                                Coming Soon
                                            </span>
                                            @endif
                                             @if($product->is_ebook == 1)
                                            <span class="badge badge-success" style="position: absolute; background-color: #28a745; color: white;">
                                                E-Book
                                            </span>
                                            @endif

                                             
                                         </div>
                                         <div class="shop-content">
                                             <h3 class="product-title-css"><a href="{{url('/book-details/'.$product->slug)}}">{{$product->name}}</a></h3>
                                            <ul class="price-list1" @if($product->is_ebook != 1) style="margin-top: 10px;" @endif>
                                                <li class="flex gap-1 align-items-start">Physical Book : ₹{{ $product->price }}/-</li>
                                                @if($product->is_ebook == 1)
                                                    <li>E-Book : ₹{{ $product->ebook_price }}/-</li>
                                                @endif
                                            </ul>
                                                @php
                                                    // allowed random ratings
                                                    $randomRatings = [3.5, 4, 4.5];

                                                    // final rating decide
                                                    $rating = ($product->rating && $product->rating > 0)
                                                        ? $product->rating
                                                        : $randomRatings[array_rand($randomRatings)];
                                                @endphp

                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($rating >= $i)
                                                            {{-- full star --}}
                                                            <i class="fa-solid fa-star text-warning"></i>

                                                        @elseif ($rating >= ($i - 0.5))
                                                            {{-- half star --}}
                                                            <i class="fa-solid fa-star-half-stroke text-warning"></i>

                                                        @else
                                                            {{-- empty star --}}
                                                            <i class="fa-regular fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                            @if($product->author_name)

                                            <p class="product-title-css">
                                                 
                                           <span class="text-black text-bold ">Author: </span> <span>{{$product->author_name}}</span> 
                                            </p>
                                                @endif
                                                   
                                              
                                                    @if($product->status != 'coming-soon')
                                             <div class="shop-button" style="padding-bottom: 15px;">
                                                 <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display:inline;">
                                                     @csrf
                                                     <input type="hidden" name="quantity" value="1">
                                                     <button type="submit" class="theme-btn">Add To Cart</button>
                                                 </form>
                                             </div>
                                             @else
<div class="shop-button" style="padding-bottom: 15px;">
                                                     <button type="button" class="theme-btn" disabled style="opacity: 0.6; cursor: not-allowed;">Add To Cart</button>
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

 
                        
                     </div>
                 </div>
             

                
             </div>
         </div>
     </div>
 </section>
 @include('layouts.store-footer')