<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="pixel-drop">
    <meta name="description" content="Speech Publications">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- ======== Page title ============ -->
    <title>Speech Publications</title>
    <!--<< Favcion >>-->
    <link rel="shortcut icon" href="{{asset('/store/assets/img/favicon.png')}}">
    <!--<< Bootstrap min.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/bootstrap.min.css')}}">
    <!--<< All Min Css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/all.min.css')}}">
    <!--<< Animate.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/animate.css')}}">
    <!--<< Magnific Popup.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/magnific-popup.css')}}">
    <!--<< MeanMenu.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/meanmenu.css')}}">
    <!--<< Swiper Bundle.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/swiper-bundle.min.css')}}">
    <!--<< Nice Select.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/nice-select.css')}}">
    <!--<< Icomoon.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/icomoon.css')}}">
    <!--<< Main.css >>-->
    <link rel="stylesheet" href="{{asset('/store/assets/css/main.css')}}">
</head>

<body>

    <!-- Cursor follower -->
    <div class="cursor-follower"></div>

    <!-- Preloader start -->
    <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner">
            </div>
            <div class="txt-loading">
                <span data-text-preloader="S" class="letters-loading">
                    S
                </span>
                <span data-text-preloader="P" class="letters-loading">
                    P
                </span>
                <span data-text-preloader="E" class="letters-loading">
                    E
                </span>
                <span data-text-preloader="E" class="letters-loading">
                    E
                </span>
                <span data-text-preloader="C" class="letters-loading">
                    C
                </span>
                <span data-text-preloader="H" class="letters-loading">
                    H
                </span>
            </div>
            <p class="text-center">Loading</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back To Top Start -->
    <button id="back-top" class="back-to-top">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <!-- Offcanvas Area start  -->
    <div class="fix-area">
        <div class="offcanvas__info">
            <div class="offcanvas__wrapper" style="background: #003366;">
                <div class="offcanvas__content">
                    <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                        <div class="offcanvas__logo">
                            <a href="{{url('/')}}">
                                <img src="{{asset('images/logo/Loggo3.png')}}" alt="logo-img">
                            </a>
                        </div>
                        <div class="offcanvas__close">
                            <button>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text d-none d-xl-block">
                        Nullam dignissim, ante scelerisque the is euismod fermentum odio sem semper the is erat, a
                        feugiat leo urna eget eros. Duis Aenean a imperdiet risus.
                    </p>
                    <div class="mobile-menu fix mb-3"></div>
                  
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas__overlay"></div>


    <header id="header-sticky" class="header-1 header-2" style="background: #003366;">
        <div class="container">
            <div class="mega-menu-wrapper">
                <div class="header-main">
                    <div class="header-left">
                        <div class="logo">
                            <a href="{{url('/')}}" class="header-logo">
                                <img src="{{asset('images/logo/Loggo3.png')}}" alt="logo-img">
                            </a>
                        </div>
                        <!--<div class="search-widget">-->
                        <!--    <form action="#">-->
                        <!--        <input type="text" placeholder="Search for Products...">-->
                        <!--        <button type="submit"><i class="fa-regular fa-magnifying-glass"></i></button>-->
                        <!--    </form>-->
                        <!--</div>-->
                    </div>
                    <div class="mean__menu-wrapper">
                        <div class="main-menu">
                            <nav id="mobile-menu">
                                <ul>
                                    <li>
                                        <a class="text-white" href="{{url('/')}}">
                                            Home
                                        </a>

                                    </li>

                                    <li>
                                        <a class="text-white " href="{{url('/store')}}">Store</a>
                                    </li>
                                       <li>
                                        <a class="text-white" href="{{url('/news')}}">News</a>
                                    </li>
                                    <li>
                                        <a class="text-white" href="{{url('/about')}}">About</a>
                                    </li>
                                   
                                    <li>
                                        <a class="text-white" href="{{url('/contact-us')}}">Contact</a>
                                    </li>


                                     <li class="d-xl-none mt-2">
                                         <a  href="{{url('/dashboard')}}" class="theme-btn text-center style-2 fadeInUp text-white" data-wow-delay=".5s">{{Auth::user()? 'Dashboard':'Login'}} <i
                                class="fa-solid fa-arrow-right-long"></i></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="header-right d-flex justify-content-end align-items-center">
                        <a class="text-white" href="{{url('/cart')}}" class="cart-icon"><i class="fa-solid fa-cart-shopping"></i></a>
                        <a  href="{{url('/dashboard')}}" class="theme-btn style-2 fadeInUp" data-wow-delay=".5s">{{Auth::user()? 'Dashboard':'Login'}} <i
                                class="fa-solid fa-arrow-right-long"></i></a>
                        <div class="header__hamburger d-xl-none my-auto">
                            <div class="sidebar__toggle">
                                <i class="text-white fas fa-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    @yield('content')

    <!-- Scripts -->
    <script src="{{asset('store/assets/js/jquery-3.7.1.min.js')}}"></script>
    <script src="{{asset('store/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Hide Preloader on Page Load -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        });

        // Alternative: Hide after a timeout if DOMContentLoaded doesn't trigger
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        });
    </script>
</body>

</html>