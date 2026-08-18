<!-- Footer Section start  -->
@php
$categories = App\Models\ProductCategory::all();
@endphp


<footer class="footer-section fix footer-bg">
    <div class="container">
        <div class="footer-widget-wrapper style-2">
            <div class="row justify-content-between">
                <div class="col-xl-4 col-lg-4 col-md-4 wow fadeInUp" data-wow-delay=".2s">
                    <div class="single-footer-widget">
                        <div class="widget-head"><a href="{{url('/')}}" class="footer-logo">
                                <img src="{{asset('/images/logo/Loggo3.png')}}" alt="logo-img">
                            </a>
                        </div>
                        <div class="footer-content">
                            <p>
                                Speech Publications attracts readers of all ages, allowing them to interact with other
                                readers and meet their favorite authors.
                            </p>
                            <div class="text">
                                <a href="tel:+919278199961">+919278199961</a>
                                <a href="mailto:speechpublications@gmail.com"
                                    class="mail-text">speechpublications@gmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-4 col-md-4 ps-lg-5 wow fadeInUp" data-wow-delay=".4s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Category</h3>
                        </div>
                        <ul class="list-items">

                        

                        @foreach($categories as $category)
                     
<li>

<a href="{{ route('store.index', ['category' => $category->id]) }}">
    {{ $category->name }}
</a>
</li>
@endforeach
                           
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-4 ps-lg-5 wow fadeInUp" data-wow-delay=".6s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Useful links</h3>
                        </div>
                        <ul class="list-items">
                            <li>
                                <a href="#">
                                    Secure Shopping
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Privacy Policy
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Terms of Use
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Shipping Policy
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Returns Policy
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Payment Option
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                    <div class="single-footer-widget">
                        <div class="widget-head">
                            <h3>Explore</h3>
                        </div>
                        <ul class="list-items">
                            <li>
                                <a href="{{url('/about')}}">
                                    About us
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/contact-us')}}">
                                    Contact us
                                </a>
                            </li>
                           
                        </ul>
                        <div class="social-icon d-flex align-items-center">
                            <a target="_blank"
                                href="https://www.facebook.com/people/Speechpublications/61581941575468/?rdid=b7EJ1C9vPYlfkfCt&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F16JX56pA43%2F"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a target="_blank" href="https://x.com/speech_p44951"><i class="fab fa-twitter"></i></a>
                            <a target="_blank"
                                href="https://www.linkedin.com/in/speechpublications-pvt-ltd-850500388/?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app"><i
                                    class="fab fa-linkedin-in"></i></a>
                            <a target="_blank" href="https://www.instagram.com/speechpublications/#" class=""><img
                                    src="images/Instagram-30px-2.svg" alt="icon" width="" height="" class="svg"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-wrapper style-1">
                <p class="wow fadeInUp" data-wow-delay=".3s">
                    ©All Rights reserved 2025 by <span>Speech Publications.</span>
                </p>
                <div class="bottom-list wow fadeInUp" data-wow-delay=".5s">
                    <div class="app-image">
                        <img src="{{asset('/store/assets/img/footer/01.png')}}" alt="img">
                    </div>
                    <div class="app-image">
                        <img src="{{asset('/store/assets/img/footer/02.png')}}" alt="img">
                    </div>
                    <div class="app-image">
                        <img src="{{asset('/store/assets/img/footer/03.png')}}" alt="img">
                    </div>
                    <div class="app-image">
                        <img src="{{asset('/store/assets/img/footer/04.png')}}" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


<!--<< All JS Plugins >>-->
<script src="{{asset('/store/assets/js/jquery-3.7.1.min.js')}}"></script>
<!--<< Viewport Js >>-->
<script src="{{asset('/store/assets/js/viewport.jquery.js')}}"></script>
<!--<< Bootstrap Js >>-->
<script src="{{asset('/store/assets/js/bootstrap.bundle.min.js')}}"></script>
<!--<< Nice Select Js >>-->
<script src="{{asset('/store/assets/js/jquery.nice-select.min.js')}}"></script>
<!--<< Waypoints Js >>-->
<script src="{{asset('/store/assets/js/jquery.waypoints.js')}}"></script>
<!--<< Counterup Js >>-->
<script src="{{asset('/store/assets/js/jquery.counterup.min.js')}}"></script>
<!--<< Swiper Slider Js >>-->
<script src="{{asset('/store/assets/js/swiper-bundle.min.js')}}"></script>
<!--<< MeanMenu Js >>-->
<script src="{{asset('/store/assets/js/jquery.meanmenu.min.js')}}"></script>
<!--<< Magnific Popup Js >>-->
<script src="{{asset('/store/assets/js/jquery.magnific-popup.min.js')}}"></script>
<!--<< Wow Animation Js >>-->
<script src="{{asset('/store/assets/js/wow.min.js')}}"></script>
<!-- Gsap -->
<script src="{{asset('/store/assets/js/gsap.min.js')}}"></script>
<!--<< Main.js >>-->
<script src="{{asset('/store/assets/js/main.js')}}"></script>
</body>

</html>