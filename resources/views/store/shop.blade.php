@include('layouts.store-header')

<link rel="stylesheet" href="{{ url('public/store/assets/css/store-beauty.css') }}">

<style>
    /* =========================================================
       SHOP PAGE
       ========================================================= */

    .shop-product-grid {
        row-gap: 28px;
    }

    .shop-product-item {
        display: flex;
    }



    /* =========================================================
       LAYOUT FIX
       Keep result bar and catalog in separate rows.
       ========================================================= */

    .shop-section .shop-result-row {
        position: relative;
        z-index: 5;
        margin-bottom: 32px !important;
    }

    .shop-section .shop-result-row > .col-12 {
        width: 100%;
    }

    .shop-section .shop-catalog-row {
        position: relative;
        z-index: 1;
        clear: both;
    }

    .shop-section .shop-catalog-row::after {
        content: "";
        display: table;
        clear: both;
    }

    /* Prevent theme animation/negative positioning from pulling
       the catalog over the result bar. */
    .shop-section .shop-catalog-row .shop-product-item {
        top: auto !important;
    }

    .shop-section .shop-catalog-row .main-sidebar {
        top: auto !important;
        margin-top: 0 !important;
    }


    /* =========================================================
       SEARCH CONTROL FIX
       Keep the green search button inside the search input.
       ========================================================= */

    .shop-section .search-container {
        position: relative !important;
    }

    .shop-section .search-container .search-input {
        width: 100%;
        padding-right: 58px !important;
    }

    .shop-section .search-container .search-icon {
        position: absolute !important;

        top: 50% !important;
        right: 10px !important;
        left: auto !important;

        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;

        margin: 0 !important;
        padding: 0 !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        transform: translateY(-50%) !important;

        border-radius: 50% !important;

        z-index: 3 !important;
    }


    /* =========================================================
       PRODUCT CARD
       ========================================================= */

    .shop-product-card {
        width: 100%;
        height: 100%;

        display: flex;
        flex-direction: column;

        background: #ffffff;
        border: 1px solid #e8ece8;
        border-radius: 16px;

        overflow: hidden;

        /* box-shadow: 0 7px 24px rgba(25, 38, 30, 0.06); */
        box-shadow: 0 16px 34px rgba(25, 38, 30, 0.12);


        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            border-color 0.25s ease;
    }

    .shop-product-card:hover {
        transform: translateY(-5px);
        border-color: rgba(87, 213, 78, 0.45);
        box-shadow: 0 16px 34px rgba(25, 38, 30, 0.12);
    }


    /* =========================================================
       PRODUCT IMAGE
       ========================================================= */

    .shop-product-image {
        position: relative;

        width: calc(100% - 20px);
        margin: 10px;

        aspect-ratio: 4 / 5;

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;

        /* border-radius: 12px;
        background: #f4f6f3; */
    }

    .shop-product-image a {
        width: 100%;
        height: 100%;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .shop-product-image img {
        display: block;

        width: 100%;
        height: 100%;

        object-fit: contain;

        transition: transform 0.35s ease;
    }

    .shop-product-card:hover .shop-product-image img {
        transform: scale(1.035);
    }


    /* =========================================================
       BADGES
       ========================================================= */

    .shop-product-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 2;

        padding: 6px 10px;

        border-radius: 999px;

        color: #ffffff;

        font-size: 10px;
        line-height: 1;
        font-weight: 700;

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.14);
    }

    .shop-product-badge.coming-soon {
        background: #dc3545;
    }

    .shop-product-badge.ebook {
        left: auto;
        right: 10px;

        background: #4d9a57;
    }


    /* =========================================================
       CARD CONTENT
       ========================================================= */

    .shop-product-content {
        flex: 1;

        display: flex;
        flex-direction: column;

        padding: 14px 15px 15px;
    }


    /* =========================================================
       TITLE
       ========================================================= */

    .shop-product-title {
        margin: 0 0 9px;

        color: #17231b;

        font-size: 14px;
        line-height: 22px;
        font-weight: 700;

        height: 42px;
        max-height: 42px;

        overflow: hidden;

        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        line-clamp: 2;
    }

    .shop-product-title a {
        color: inherit;
        text-decoration: none;
    }

    .shop-product-title a:hover {
        color: #619f5d;
    }

    /* =========================================================
       PRICE
       ========================================================= */

    .shop-product-prices {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;

        margin-bottom: 6px;
    }

    .shop-product-price {
        display: inline-flex;
        align-items: center;

        padding: 5px 8px;

        border-radius: 7px;

        background: #f3f7f2;

        color: #547452;

        font-size: 10px;
        line-height: 1.2;
        font-weight: 600;
    }

    .shop-product-price strong {
        margin-left: 4px;

        color: #1d2b21;

        font-size: 12px;
        font-weight: 700;
    }


    /* =========================================================
       RATING
       ========================================================= */

    .shop-product-rating {
        display: flex;
        align-items: center;

        margin-bottom: 6px;

        min-height: 18px;
    }

    .shop-product-rating-stars {
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .shop-product-rating-stars i {
        color: #f4b400;
        font-size: 11px;
    }

    .shop-product-rating-value {
        margin-left: 5px;

        color: #737b76;

        font-size: 11px;
        font-weight: 600;
    }


    /* =========================================================
       AUTHOR
       ========================================================= */

    .shop-product-author {
        margin: 0 0 12px;

        color: #6d756f;

        font-size: 11px;
        line-height: 18px;

        height: 36px;
        max-height: 36px;

        overflow: hidden;

        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        line-clamp: 2;
    }

    .shop-product-author-label {
        color: #26352c;
        font-weight: 700;
    }


    /* =========================================================
       CART BUTTON
       ========================================================= */

    .shop-product-action {
        margin-top: auto;
    }

    .shop-product-action form {
        margin: 0;
    }

    .shop-product-cart {
        width: 100%;
        min-height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 0;
        border-radius: 10px;

        background: #78b574;
        color: #ffffff;

        font-size: 12px;
        line-height: 1;
        font-weight: 700;

        box-shadow: 0 6px 14px rgba(120, 181, 116, 0.20);

        transition:
            background 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .shop-product-cart:hover:not(:disabled) {
        background: #619f5d;

        transform: translateY(-1px);

        box-shadow: 0 9px 18px rgba(120, 181, 116, 0.28);
    }

    .shop-product-cart i {
        margin-right: 7px;
    }

    .shop-product-cart:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        box-shadow: none;
    }


    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .shop-empty-state {
        width: 100%;

        padding: 50px 20px;

        text-align: center;

        border: 1px dashed #dce3dc;
        border-radius: 14px;

        background: #fafcf9;
    }

    .shop-empty-state h4 {
        margin: 0;

        color: #6c757d;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 1199px) {

        .shop-product-image {
            aspect-ratio: 4 / 5;
        }

    }

    @media (max-width: 991px) {

        .shop-product-grid {
            row-gap: 24px;
        }

        .shop-product-content {
            padding: 13px;
        }

        .shop-product-title {
            font-size: 14px;
        }

    }

    @media (max-width: 767px) {

        .shop-product-grid {
            row-gap: 20px;
        }

        .shop-product-image {
            aspect-ratio: 4 / 5;
        }

        .shop-product-title {
            font-size: 15px;
        }

        .shop-product-price {
            font-size: 10px;
        }

    }

    @media (max-width: 575px) {

        .shop-product-image {
            aspect-ratio: 4 / 5;
        }

        .shop-product-content {
            padding: 10px;
        }

    }
</style>


<!-- =========================================================
     BREADCRUMB
========================================================= -->

<div class="breadcrumb-wrapper bg-cover section-padding"
     style="background-image: url(public/store/assets/img/hero/breadcrumb-bg.jpg);">

    <div class="container">

        <div class="page-heading">

            <h1>Shop</h1>

            <div class="page-header">

                <ul class="breadcrumb-items wow fadeInUp"
                    data-wow-delay=".3s">

                    <li>
                        <a href="{{ url('/') }}">
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


<!-- =========================================================
     SHOP
========================================================= -->

<section class="shop-section fix section-padding">

    <div class="container">

        <div class="shop-default-wrapper">

            <!-- =====================================================
                 RESULT BAR ROW
            ====================================================== -->

            <div class="row shop-result-row">

                <div class="col-12">

                    @php
                        $from = $products->total() > 0
                            ? (($products->currentPage() - 1) * $products->perPage()) + 1
                            : 0;

                        $to = $products->total() > 0
                            ? min(
                                $products->currentPage() * $products->perPage(),
                                $products->total()
                            )
                            : 0;
                    @endphp

                    <div class="woocommerce-notices-wrapper wow fadeInUp"
                         data-wow-delay=".3s">

                        <p>
                            Showing {{ $from }} - {{ $to }}
                            Of {{ $products->total() }} Results
                        </p>

                        <div class="form-clt">

                            <div class="nice-select" tabindex="0">

                                <span class="current">
                                    <!-- Default Sorting -->
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 CATALOG ROW
            ========================================================== -->

            <div class="row shop-catalog-row">


                <!-- =====================================================
                     SIDEBAR
                ====================================================== -->

                <div class="col-xl-3 col-lg-4 order-md-1 wow fadeInUp"
                     data-wow-delay=".3s">

                    <div class="main-sidebar">


                        <!-- SEARCH -->

                        <div class="single-sidebar-widget">

                            <div class="wid-title">
                                <h5>Search</h5>
                            </div>

                            <form action="{{ route('store.index') }}"
                                  method="GET"
                                  class="search-toggle-box">

                                <div class="input-area search-container">

                                    <input
                                        class="search-input text-black"
                                        type="text"
                                        name="search"
                                        placeholder="Search by book or author"
                                        value="{{ request('search') }}"
                                    >

                                    <button
                                        type="submit"
                                        class="cmn-btn search-icon"
                                    >
                                        <i class="far fa-search"></i>
                                    </button>

                                </div>

                            </form>

                        </div>


                        <!-- CATEGORIES -->

                        <div class="single-sidebar-widget">

                            <div class="wid-title">
                                <h5>Categories</h5>
                            </div>

                            <div class="categories-list">

                                <ul class="nav nav-pills mb-3"
                                    id="pills-tab"
                                    role="tablist">


                                    <!-- ALL PRODUCTS -->

                                    <li class="nav-item"
                                        role="presentation">

                                        <a
                                            style="padding: 10px;"
                                            class="nav-link {{ !request('category') ? 'active' : '' }}"
                                            href="{{ route('store.index', request()->except('category', 'page')) }}"
                                            role="tab"
                                        >
                                            All Products
                                        </a>

                                    </li>


                                    <!-- CATEGORY LIST -->

                                    @foreach($categories as $category)

                                        <li class="nav-item"
                                            role="presentation">

                                            @php
                                                $categoryParams = request()->except('page', 'category');
                                                $categoryParams['category'] = $category->id;
                                            @endphp

                                            <a
                                                style="padding: 10px;"
                                                class="nav-link {{ request('category') == $category->id ? 'active' : '' }}"
                                                href="{{ route('store.index', $categoryParams) }}"
                                                role="tab"
                                            >
                                                {{ $category->name }}
                                            </a>

                                        </li>

                                    @endforeach


                                </ul>

                            </div>

                        </div>


                    </div>

                </div>


                <!-- =====================================================
                     PRODUCT AREA
                ====================================================== -->

                <div class="col-xl-9 col-lg-8 order-md-2 shop-product-area">

                    <div class="tab-content"
                         id="pills-tabContent">

                        <div
                            class="tab-pane fade show active"
                            id="pills-arts"
                            role="tabpanel"
                            aria-labelledby="pills-arts-tab"
                            tabindex="0"
                        >

                            <div class="row shop-product-grid">


                                <!-- =================================================
                                     EMPTY
                                ================================================== -->

                                @if($products->isEmpty())

                                    <div class="col-12">

                                        <div class="shop-empty-state">

                                            <h4>
                                                No products found.
                                            </h4>

                                        </div>

                                    </div>


                                @else


                                    <!-- =================================================
                                         PRODUCTS
                                    ================================================== -->

                                    @foreach($products as $product)

                                        @php
                                            $images = json_decode(
                                                $product->image,
                                                true
                                            ) ?? [];

                                            $imageUrl = (
                                                !empty($images)
                                                && isset($images[0])
                                            )
                                                ? Storage::disk('s3')->url(
                                                    'product/' . $images[0]
                                                )
                                                : asset(
                                                    'images/no-image.png'
                                                );

                                            /*
                                             * Do not generate a random rating on
                                             * every page refresh. Use the stored
                                             * product rating and a stable fallback.
                                             */
                                            $rating = (
                                                $product->rating
                                                && $product->rating > 0
                                            )
                                                ? min((float) $product->rating, 5)
                                                : 4.0;
                                        @endphp


                                        <!-- PRODUCT -->

                                        <div
                                            class="col-xl-3 col-lg-4 col-md-6 col-6 shop-product-item wow fadeInUp"
                                            data-wow-delay=".1s"
                                        >

                                            <article class="shop-product-card">


                                                <!-- IMAGE -->

                                                <div class="shop-product-image">

                                                    <a
                                                        href="{{ url('/book-details/' . $product->slug) }}"
                                                        aria-label="{{ $product->name }}"
                                                    >

                                                        <img
                                                            src="{{ $imageUrl }}"
                                                            alt="{{ $product->name }}"
                                                            loading="lazy"
                                                        >

                                                    </a>


                                                    @if($product->status === 'coming-soon')

                                                        <span class="shop-product-badge coming-soon">
                                                            Coming Soon
                                                        </span>

                                                    @endif

                                                </div>


                                                <!-- CONTENT -->

                                                <div class="shop-product-content">


                                                    <!-- TITLE -->

                                                    <h3 class="shop-product-title">

                                                        <a
                                                            href="{{ url('/book-details/' . $product->slug) }}"
                                                            title="{{ $product->name }}"
                                                        >
                                                            {{ $product->name }}
                                                        </a>

                                                    </h3>


                                                    <!-- PRICE -->

                                                    <div class="shop-product-prices">

                                                        <span class="shop-product-price">

                                                            <strong>
                                                                ₹{{ number_format((float) $product->price, 0) }}
                                                            </strong>

                                                        </span>


                                                        @if((int) $product->is_ebook === 1)

                                                            <span class="shop-product-price">

                                                                E-Book

                                                                <strong>
                                                                    ₹{{ number_format((float) $product->ebook_price, 0) }}
                                                                </strong>

                                                            </span>

                                                        @endif

                                                    </div>


                                                    <!-- RATING -->

                                                    <div class="shop-product-rating">

                                                        <span class="shop-product-rating-stars">

                                                            @for($i = 1; $i <= 5; $i++)

                                                                @if($rating >= $i)

                                                                    <i class="fa-solid fa-star"></i>

                                                                @elseif($rating >= ($i - 0.5))

                                                                    <i class="fa-solid fa-star-half-stroke"></i>

                                                                @else

                                                                    <i class="fa-regular fa-star"></i>

                                                                @endif

                                                            @endfor

                                                        </span>

                                                        <span class="shop-product-rating-value">
                                                            {{ number_format($rating, 1) }}
                                                        </span>

                                                    </div>


                                                    <!-- AUTHOR -->

                                                    @if(filled($product->author_name))

                                                        <p class="shop-product-author">

                                                            <span class="shop-product-author-label">
                                                                Author:
                                                            </span>

                                                            {{ $product->author_name }}

                                                        </p>

                                                    @else

                                                        <p class="shop-product-author">
                                                            &nbsp;
                                                        </p>

                                                    @endif


                                                    <!-- ACTION -->

                                                    <div class="shop-product-action">

                                                        @if($product->status !== 'coming-soon')

                                                            <form
                                                                action="{{ route('cart.add', $product->id) }}"
                                                                method="POST"
                                                            >

                                                                @csrf

                                                                <input
                                                                    type="hidden"
                                                                    name="quantity"
                                                                    value="1"
                                                                >

                                                                <button
                                                                    type="submit"
                                                                    class="shop-product-cart"
                                                                >

                                                                    <i class="fa-solid fa-bag-shopping"></i>

                                                                    Add To Cart

                                                                </button>

                                                            </form>

                                                        @else

                                                            <button
                                                                type="button"
                                                                class="shop-product-cart"
                                                                disabled
                                                            >

                                                                <i class="fa-solid fa-clock"></i>

                                                                Coming Soon

                                                            </button>

                                                        @endif

                                                    </div>


                                                </div>

                                            </article>

                                        </div>

                                    @endforeach


                                @endif


                            </div>

                        </div>

                    </div>


                    <!-- =====================================================
                         PAGINATION
                    ====================================================== -->

                    <div class="page-nav-wrap text-center">

                        {{ $products->links('pagination::bootstrap-4') }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


@include('layouts.store-footer')
