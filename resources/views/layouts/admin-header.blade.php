<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="SpeechPublications">
    <meta name="keywords" content="">
    <title>Speech Publications</title>
    <script src="{{ url('public/store/assets/js/color-modes.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com/">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/store/assets/css/core.css') }}">
    <link rel="stylesheet" href="{{ url('public/store/assets/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/store/assets/css/style.css') }}">
    <link rel="shortcut icon" href="https://speechpublications.com/public/images/logo/Loggo3.png" />


</head>

<body>
    <div class="main-wrapper">

        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-brand">
                    Speech<span></span>
                </a>
                <div class="sidebar-toggler">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="sidebar-body">
                <ul class="nav" id="sidebarNav">
                 
                    <li class="nav-item">
                        <a href="{{url('/dashboard')}}" class="nav-link">
                            <i class="link-icon" data-lucide="box"></i>
                            <span class="link-title">Dashboard</span>
                        </a>
                    </li>
                    @if(Auth::user()->hasRole('admin'))
                   
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#emails" role="button" aria-expanded="false"
                            aria-controls="emails">
                            <i class="link-icon" data-lucide="user"></i>
                            <span class="link-title">Testimonial</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="emails">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/testimonial-list')}}" class="nav-link">List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/testimonial-add')}}" class="nav-link">Create</a>
                                </li>

                            </ul>
                        </div>
                    </li>


                   
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#category" role="button"
                            aria-expanded="false" aria-controls="category">
                            <i class="link-icon" data-lucide="user"></i>
                            <span class="link-title">Product Category</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="category">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/product-category-list')}}" class="nav-link">List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/product-category-create')}}" class="nav-link">Create</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/admin/popular-books')}}" class="nav-link">Popular Books</a>
                                </li>

                            </ul>
                        </div>
                    </li>

                  
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#product" role="button"
                            aria-expanded="false" aria-controls="product">
                            <i class="link-icon" data-lucide="user"></i>
                            <span class="link-title">Product</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="product">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/product-list')}}" class="nav-link">List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/product-create')}}" class="nav-link">Create</a>
                                </li>

                              

                            </ul>
                        </div>
                    </li>

                
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#order-history" role="button"
                            aria-expanded="false" aria-controls="order-history">
                            <i class="link-icon" data-lucide="user"></i>
                            <span class="link-title">Order History</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="order-history">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/user-order-history')}}" class="nav-link">List</a>
                                </li>

                            </ul>
                        </div>
                    </li>


                    @endif

                    @if(Auth::user()->hasRole('user'))
                    <li class="nav-item nav-category">Order</li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#product" role="button"
                            aria-expanded="false" aria-controls="product">
                            <i class="link-icon" data-lucide="user"></i>
                            <span class="link-title">Order History</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="product">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/order-history')}}" class="nav-link">List</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endif
                   
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#news-menu" role="button"
                            aria-expanded="false" aria-controls="news-menu">
                            <i class="link-icon" data-lucide="newspaper"></i>
                            <span class="link-title">News</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="news-menu">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/admin-news')}}" class="nav-link">List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/admin-news/create')}}" class="nav-link">Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#news-author" role="button"
                            aria-expanded="false" aria-controls="news-author">
                            <i class="link-icon" data-lucide="user"></i>
                            <span class="link-title">News Authors</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="news-author">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/admin-news-author')}}" class="nav-link">List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/admin-news-author/create')}}" class="nav-link">Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#news-category1" role="button"
                            aria-expanded="false" aria-controls="news-category">
                            <i class="link-icon" data-lucide="tag"></i>
                            <span class="link-title">News Categories</span>
                            <i class="link-arrow" data-lucide="chevron-down"></i>
                        </a>
                        <div class="collapse" data-bs-parent="#sidebarNav" id="news-category1">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{url('/admin-news-category')}}" class="nav-link">List</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{url('/admin-news-category/create')}}" class="nav-link">Create</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    




                </ul>

            </div>
         
        </nav>

     