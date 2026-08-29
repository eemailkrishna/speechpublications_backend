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
       RESULT BAR + SEARCH + CATEGORY UI
       Product-card styles are intentionally untouched.
       ========================================================= */

    .shop-section .shop-result-row {
        position: relative;
        z-index: 5;
        margin-bottom: 30px !important;
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

    .shop-section .shop-catalog-row .shop-product-item,
    .shop-section .shop-catalog-row .main-sidebar {
        top: auto !important;
    }

    .shop-section .shop-catalog-row .main-sidebar {
        margin-top: 0 !important;
    }

    /* =========================================================
       RESULT BAR
       ========================================================= */

    .shop-section .woocommerce-notices-wrapper {
        position: relative;

        min-height: 70px;
        padding: 0 24px 0 28px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        background: #ffffff;

        border: 1px solid #e4ebe5;
        border-radius: 15px;

        box-shadow: 0 8px 28px rgba(30, 45, 34, 0.055);

        overflow: hidden;
    }

    .shop-section .woocommerce-notices-wrapper::before {
        content: "";

        position: absolute;
        left: 0;
        top: 0;

        width: 4px;
        height: 100%;

        background: #78b574;
    }

    .shop-section .woocommerce-notices-wrapper::after {
        content: "";

        position: absolute;
        left: 20px;
        bottom: 0;

        width: 70px;
        height: 2px;

        border-radius: 10px;

        background: rgba(120, 181, 116, 0.18);
    }

    .shop-section .woocommerce-notices-wrapper p {
        margin: 0;
        padding-left: 0;

        color: #46534b;

        font-size: 16px;
        line-height: 1.4;
        font-weight: 600;

        letter-spacing: 0.1px;
    }

    /* No sorting UI is currently needed */
    .shop-section .woocommerce-notices-wrapper .form-clt {
        display: none !important;
    }

    /* =========================================================
       SIDEBAR
       ========================================================= */

    .shop-section .main-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .shop-section .single-sidebar-widget {
        position: relative;

        margin: 0 !important;
        padding: 20px;

        background: #ffffff;

        border: 1px solid #e7ece8;
        border-radius: 15px;

        box-shadow:
            0 7px 24px rgba(30, 45, 34, 0.045);

        overflow: hidden;
    }

    .shop-section .single-sidebar-widget::before {
        content: "";

        position: absolute;
        top: 0;
        left: 0;

        width: 100%;
        height: 2px;

        background: linear-gradient(
            90deg,
            #78b574 0,
            #78b574 22%,
            rgba(120, 181, 116, 0.08) 55%,
            transparent 100%
        );
    }

    /* =========================================================
       SIDEBAR HEADINGS
       ========================================================= */

    .shop-section .single-sidebar-widget .wid-title {
        margin-bottom: 16px;
    }

    .shop-section .single-sidebar-widget .wid-title h5 {
        position: relative;

        margin: 0;
        padding: 0 0 11px;

        color: #142119;

        font-size: 21px;
        line-height: 1.25;
        font-weight: 700;

        letter-spacing: -0.2px;
    }

    .shop-section .single-sidebar-widget .wid-title h5::after {
        content: "";

        position: absolute;
        left: 0;
        bottom: 0;

        width: 48px;
        height: 3px;

        border-radius: 50px;

        background: #78b574;

        box-shadow:
            17px 0 0 rgba(120, 181, 116, 0.15);
    }

    /* =========================================================
       SEARCH
       ========================================================= */

    .shop-section .search-toggle-box,
    .shop-section .search-toggle-box .input-area {
        margin: 0;
    }

    .shop-section .search-container {
        position: relative !important;

        width: 100%;
        min-height: 52px;
    }

    .shop-section .search-container .search-input {
        width: 100% !important;
        height: 52px !important;

        margin: 0 !important;
        padding: 0 62px 0 16px !important;

        box-sizing: border-box !important;

        border: 1px solid #dbe4dc !important;
        border-radius: 12px !important;

        background: #fbfcfb !important;

        color: #26352c !important;

        font-size: 13px !important;
        line-height: 1.2 !important;

        outline: none !important;

        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;

        transition:
            border-color .2s ease,
            background .2s ease,
            box-shadow .2s ease;
    }

    .shop-section .search-container .search-input::placeholder {
        color: #89948d;
        opacity: 1;
    }

    .shop-section .search-container .search-input:hover {
        border-color: #c7d8ca !important;
        background: #ffffff !important;
    }

    .shop-section .search-container .search-input:focus {
        border-color: #78b574 !important;

        background: #ffffff !important;

        box-shadow:
            0 0 0 3px rgba(120, 181, 116, 0.11),
            0 5px 15px rgba(40, 70, 45, 0.04) !important;
    }

    .shop-section .search-container .search-icon {
        position: absolute !important;

        top: 50% !important;
        right: 7px !important;
        left: auto !important;

        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;

        margin: 0 !important;
        padding: 0 !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        transform: translateY(-50%) !important;

        border: 0 !important;
        border-radius: 50% !important;

        background: #78b574 !important;
        color: #ffffff !important;

        box-shadow:
            0 5px 13px rgba(83, 143, 79, 0.20);

        z-index: 3 !important;

        cursor: pointer;

        transition:
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .shop-section .search-container .search-icon:hover {
        background: #619f5d !important;

        transform: translateY(-50%) scale(1.04) !important;

        box-shadow:
            0 7px 16px rgba(83, 143, 79, 0.25);
    }

    .shop-section .search-container .search-icon:active {
        transform: translateY(-50%) scale(.98) !important;
    }

    .shop-section .search-container .search-icon svg {
        display: block;

        width: 17px;
        height: 17px;

        flex: 0 0 auto;
    }

    /* =========================================================
       CATEGORIES
       ========================================================= */

    .shop-section .categories-list {
        margin: 0;
    }

    .shop-section .categories-list ul {
        display: flex;
        flex-direction: column;

        gap: 9px;

        margin: 0 !important;
        padding: 0 !important;

        list-style: none;
    }

    .shop-section .categories-list .nav-item {
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }

    .shop-section .categories-list .nav-link {
        position: relative;

        width: 100%;
        min-height: 47px;

        margin: 0 !important;
        padding: 10px 14px 10px 15px !important;

        display: flex;
        align-items: center;

        box-sizing: border-box;

        border: 1px solid #dfe7e0 !important;
        border-radius: 10px !important;

        background: #ffffff !important;

        color: #35443a !important;

        font-size: 12px !important;
        line-height: 1.35 !important;
        font-weight: 500 !important;

        text-decoration: none !important;

        overflow: hidden;

        transition:
            color .2s ease,
            background .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .shop-section .categories-list .nav-link::before {
        content: "";

        position: absolute;
        left: 0;
        top: 50%;

        width: 3px;
        height: 0;

        border-radius: 0 5px 5px 0;

        background: #78b574;

        transform: translateY(-50%);

        transition: height .2s ease;
    }

    .shop-section .categories-list .nav-link:hover {
        border-color: #b8d5b5 !important;

        background: #f7fbf7 !important;

        color: #4f8d4d !important;

        transform: translateX(2px);

        box-shadow:
            0 4px 12px rgba(60, 95, 64, 0.055);
    }

    .shop-section .categories-list .nav-link:hover::before {
        height: 20px;
    }

    .shop-section .categories-list .nav-link.active {
        border-color: #78b574 !important;

        background: #78b574 !important;

        color: #ffffff !important;

        font-weight: 600 !important;

        transform: none;

        box-shadow:
            0 7px 16px rgba(83, 143, 79, 0.19);
    }

    .shop-section .categories-list .nav-link.active::before {
        height: 22px;
        background: rgba(255, 255, 255, 0.7);
    }

    /* =========================================================
       UI RESPONSIVE
       ========================================================= */

    @media (max-width: 991px) {

        .shop-section .shop-result-row {
            margin-bottom: 24px !important;
        }

        .shop-section .woocommerce-notices-wrapper {
            min-height: 64px;
            padding: 0 20px 0 23px;
        }

        .shop-section .woocommerce-notices-wrapper p {
            font-size: 15px;
        }

        .shop-section .single-sidebar-widget {
            padding: 18px;
        }

        .shop-section .main-sidebar {
            gap: 17px;
        }
    }

    @media (max-width: 767px) {

        .shop-section .shop-result-row {
            margin-bottom: 20px !important;
        }

        .shop-section .woocommerce-notices-wrapper {
            min-height: 57px;
            padding: 0 15px 0 18px;

            border-radius: 12px;
        }

        .shop-section .woocommerce-notices-wrapper p {
            font-size: 13px;
        }

        .shop-section .main-sidebar {
            gap: 14px;
        }

        .shop-section .single-sidebar-widget {
            padding: 16px;
            border-radius: 13px;
        }

        .shop-section .single-sidebar-widget .wid-title {
            margin-bottom: 14px;
        }

        .shop-section .single-sidebar-widget .wid-title h5 {
            font-size: 19px;
        }

        .shop-section .search-container .search-input {
            height: 48px !important;
            padding-right: 55px !important;
        }

        .shop-section .search-container .search-icon {
            width: 36px !important;
            height: 36px !important;
            min-width: 36px !important;

            right: 6px !important;
        }

        .shop-section .search-container .search-icon svg {
            width: 16px;
            height: 16px;
        }

        .shop-section .categories-list ul {
            gap: 8px;
        }

        .shop-section .categories-list .nav-link {
            min-height: 44px;

            padding: 9px 12px 9px 13px !important;

            font-size: 12px !important;
        }
    }

    @media (max-width: 575px) {

        .shop-section .woocommerce-notices-wrapper {
            min-height: 54px;
            border-radius: 11px;
        }

        .shop-section .woocommerce-notices-wrapper p {
            font-size: 12.5px;
        }

        .shop-section .single-sidebar-widget {
            padding: 14px;
            border-radius: 12px;
        }

        .shop-section .single-sidebar-widget .wid-title h5 {
            font-size: 18px;
        }

        .shop-section .categories-list ul {
            gap: 7px;
        }

        .shop-section .categories-list .nav-link {
            min-height: 42px;
        }
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
       SIDEBAR / RESULT RESPONSIVE ONLY
       ========================================================= */

    @media (max-width: 991px) {
        .shop-section .woocommerce-notices-wrapper {
            min-height: 62px;
            padding: 0 20px;
        }

        .shop-section .single-sidebar-widget {
            padding: 18px;
        }
    }

    @media (max-width: 767px) {
        .shop-section .shop-result-row {
            margin-bottom: 22px !important;
        }

        .shop-section .woocommerce-notices-wrapper {
            min-height: 58px;
            padding: 0 16px;
            border-radius: 12px;
        }

        .shop-section .woocommerce-notices-wrapper p {
            font-size: 14px;
        }

        .shop-section .main-sidebar {
            gap: 14px;
        }

        .shop-section .single-sidebar-widget {
            padding: 16px;
            border-radius: 13px;
        }

        .shop-section .single-sidebar-widget .wid-title {
            margin-bottom: 14px;
        }

        .shop-section .single-sidebar-widget .wid-title h5 {
            font-size: 19px;
        }

        .shop-section .search-container .search-input {
            height: 48px !important;
            padding-right: 55px !important;
        }

        .shop-section .search-container .search-icon {
            width: 36px !important;
            height: 36px !important;
            min-width: 36px !important;
            right: 6px !important;
        }

        .shop-section .search-container .search-icon svg {
            width: 16px;
            height: 16px;
        }

        .shop-section .categories-list ul {
            gap: 8px;
        }

        .shop-section .categories-list .nav-link {
            min-height: 45px;
            padding: 9px 12px !important;
            font-size: 12px !important;
        }
    }

    @media (max-width: 575px) {
        .shop-section .woocommerce-notices-wrapper {
            min-height: 54px;
        }

        .shop-section .woocommerce-notices-wrapper p {
            font-size: 13px;
        }

        .shop-section .single-sidebar-widget {
            padding: 14px;
        }
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


/* =========================================================
   FINAL CLEAN SIDEBAR OVERRIDES
   Search / Categories / Result Bar ONLY
   Product cards remain untouched.
   ========================================================= */
.shop-section .single-sidebar-widget::before,
.shop-section .single-sidebar-widget::after,
.shop-section .single-sidebar-widget .wid-title h5::before {
    content: none !important;
    display: none !important;
}

.shop-section .single-sidebar-widget {
    margin: 0 0 18px !important;
    padding: 20px !important;
    background: #fff !important;
    border: 1px solid #e7ece8 !important;
    border-radius: 16px !important;
    box-shadow: 0 6px 22px rgba(25, 38, 30, .045) !important;
    overflow: visible !important;
}

.shop-section .single-sidebar-widget .wid-title {
    margin: 0 0 17px !important;
}

.shop-section .single-sidebar-widget .wid-title h5 {
    position: relative !important;
    margin: 0 !important;
    padding: 0 0 11px !important;
    color: #142119 !important;
    font-size: 21px !important;
    line-height: 1.25 !important;
    font-weight: 700 !important;
}

.shop-section .single-sidebar-widget .wid-title h5::after {
    content: "" !important;
    display: block !important;
    position: absolute !important;
    left: 0 !important;
    bottom: 0 !important;
    width: 46px !important;
    height: 3px !important;
    border-radius: 50px !important;
    background: #78b574 !important;
    box-shadow: 17px 0 0 rgba(120,181,116,.14) !important;
}

/* Search */
.shop-section .search-container {
    position: relative !important;
    width: 100% !important;
    min-height: 50px !important;
}

.shop-section .search-container .search-input {
    width: 100% !important;
    height: 50px !important;
    box-sizing: border-box !important;
    margin: 0 !important;
    padding: 0 58px 0 15px !important;
    border: 1px solid #dbe5dc !important;
    border-radius: 12px !important;
    background: #fbfdfb !important;
    color: #26352c !important;
    font-size: 13px !important;
    outline: none !important;
    transition: .2s ease !important;
}

.shop-section .search-container .search-input:focus {
    border-color: #78b574 !important;
    background: #fff !important;
    box-shadow: 0 0 0 3px rgba(120,181,116,.10) !important;
}

.shop-section .search-container .search-icon {
    position: absolute !important;
    top: 50% !important;
    right: 6px !important;
    left: auto !important;
    width: 38px !important;
    height: 38px !important;
    min-width: 38px !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transform: translateY(-50%) !important;
    border: 0 !important;
    border-radius: 50% !important;
    background: #78b574 !important;
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(83,143,79,.18) !important;
    z-index: 2 !important;
}

.shop-section .search-container .search-icon svg {
    display: block !important;
    width: 17px !important;
    height: 17px !important;
}

/* Categories */
.shop-section .categories-list ul {
    display: flex !important;
    flex-direction: column !important;
    gap: 8px !important;
    margin: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}

.shop-section .categories-list .nav-item {
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

.shop-section .categories-list .nav-link,
.shop-section .categories-list .nav-link.active {
    position: relative !important;
    width: 100% !important;
    min-height: 45px !important;
    margin: 0 !important;
    padding: 10px 14px !important;
    display: flex !important;
    align-items: center !important;
    box-sizing: border-box !important;
    border: 1px solid #dfe7e0 !important;
    border-radius: 10px !important;
    background: #fff !important;
    color: #344239 !important;
    font-size: 12px !important;
    line-height: 1.35 !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    transform: none !important;
    box-shadow: none !important;
    transition: background .18s ease, border-color .18s ease, color .18s ease, box-shadow .18s ease !important;
}

.shop-section .categories-list .nav-link::before {
    content: none !important;
    display: none !important;
}

.shop-section .categories-list .nav-link:hover {
    border-color: #b9d7b6 !important;
    background: #f7fbf7 !important;
    color: #4f8d4d !important;
    transform: none !important;
    box-shadow: 0 3px 10px rgba(60,95,64,.05) !important;
}

.shop-section .categories-list .nav-link.active {
    border-color: #78b574 !important;
    background: #78b574 !important;
    color: #fff !important;
    font-weight: 600 !important;
    box-shadow: 0 5px 13px rgba(83,143,79,.16) !important;
}

@media (max-width: 767px) {
    .shop-section .single-sidebar-widget {
        margin-bottom: 14px !important;
        padding: 16px !important;
        border-radius: 13px !important;
    }

    .shop-section .single-sidebar-widget .wid-title {
        margin-bottom: 14px !important;
    }

    .shop-section .single-sidebar-widget .wid-title h5 {
        font-size: 19px !important;
    }

    .shop-section .categories-list ul {
        gap: 7px !important;
    }

    .shop-section .categories-list .nav-link,
    .shop-section .categories-list .nav-link.active {
        min-height: 43px !important;
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
                                        aria-label="Search"
                                    >
                                        <svg
                                            width="18"
                                            height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            aria-hidden="true"
                                        >
                                            <circle
                                                cx="11"
                                                cy="11"
                                                r="7"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            />
                                            <path
                                                d="M16.5 16.5L21 21"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                            />
                                        </svg>
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
