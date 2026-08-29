@extends('layouts.app')
@section('content')


 <style>
        .content { 
            max-height: 130px; 
            overflow: hidden; 
            transition: max-height 0.4s;
            position: relative;
        }
        .content1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(transparent);
            pointer-events: none;
        }
        .content.show { max-height: 2000px; }
        .content.show::after { display: none; }

        /* Homepage responsive adjustments */
        .wrapper { padding: 0 15px; }
        .banner_slider .bg_img { width: 100%; height: auto; object-fit: cover; display:block; }
        .vision_item figure img { width:232px; height:232px; object-fit:cover; }
        .card-book img { width:100%; max-width:200px; height:auto; margin:0 auto; display:block; }
        .book-thumb { width:200px; height:200px; object-fit:cover; display:block; margin:0 auto; }

        @media (max-width: 992px) {
            .vision_item figure img { width:160px; height:160px; }
            .home-highlights-popular .col-md-4 { flex: 0 0 50%; max-width:50%; }
            .home-highlights-popular .col-6.col-md-3 { flex: 0 0 33.3333%; max-width:33.3333%; }
        }

        @media (max-width: 768px) {
            .vision_item figure img { width:120px; height:120px; }
            .home-highlights-popular .col-md-4 { flex: 0 0 100%; max-width:100%; }
            .home-highlights-popular .col-6.col-md-3 { flex: 0 0 50%; max-width:50%; }
            .card-book { padding: 8px; }
            .book-thumb { max-width:100%; width:100%; height:auto; }
            .mt-10 { margin-top: 10px; }
            .banner_slider .wrapped { padding: 12px 0; }
            .description_box .content { max-height: 100px; }
            .review_item .heading_box { gap:8px; }
        }

        @media (max-width: 480px) {
            .vision_item figure img { width:80px; height:80px; }
            .card-book img { max-width:150px; }
            .description_box h4, .h2, .h1 { font-size: 1rem; }
            .common_btn { padding: 8px 12px; font-size: 14px; }
            .review_item .heading_box figure img { width:64px; height:64px; }
            .intro_box h2 { font-size: 1.25rem; }
        }
    </style>
<section class="banner_slider" style="background:#003366">

    <div class="slider_box">
        <div class="slide banner_container position-relative">
            <ul class="elements" style="background:#003366">
                <li class="vertical_move"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 18 18" fill="none">
                        <path
                            d="M9.00004 6.62496L2.37499 0L0 2.375L6.62505 8.99996L0 15.625L2.37499 18L9.00004 11.3749L15.625 18L18 15.625L11.375 8.99996L18 2.375L15.625 0L9.00004 6.62496Z"
                            fill="white"></path>
                    </svg></li>
                <li class="vertical_move"><svg xmlns="http://www.w3.org/2000/svg" width="21" height="33"
                        viewBox="0 0 21 33" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M17.1236 4.42973L9.28918 0L4.85945 7.83438L0.429719 15.6688L8.2641 20.0985L3.83436 27.9329L11.6687 32.3626L16.0985 24.5282L20.5282 16.6938L12.6938 12.2641L17.1236 4.42973Z"
                            fill="white"></path>
                    </svg></li>
                <li class="zoom"><svg xmlns="http://www.w3.org/2000/svg" width="55" height="58" viewBox="0 0 55 58"
                        fill="none">
                        <path
                            d="M0.960636 29.5231H25.9916L12.5278 42.3755C12.4306 42.4818 12.3794 42.6194 12.3844 42.7604C12.3894 42.9014 12.4503 43.0353 12.5548 43.1351C12.6593 43.2349 12.7996 43.293 12.9472 43.2978C13.0949 43.3026 13.239 43.2537 13.3503 43.1609L26.8141 30.3085V57.2485C26.8074 57.3253 26.8175 57.4025 26.8436 57.4753C26.8698 57.5481 26.9115 57.6149 26.9661 57.6715C27.0207 57.7282 27.087 57.7734 27.1608 57.8043C27.2347 57.8352 27.3144 57.8512 27.395 57.8512C27.4756 57.8512 27.5554 57.8352 27.6292 57.8043C27.703 57.7734 27.7693 57.7282 27.8239 57.6715C27.8785 57.6149 27.9202 57.5481 27.9464 57.4753C27.9726 57.4025 27.9826 57.3253 27.976 57.2485V30.3047L41.4357 43.1609C41.5471 43.2537 41.6911 43.3026 41.8388 43.2978C41.9865 43.293 42.1268 43.2349 42.2313 43.1351C42.3357 43.0353 42.3967 42.9014 42.4017 42.7604C42.4067 42.6194 42.3554 42.4818 42.2583 42.3755L28.7985 29.5231H53.8294C53.984 29.5231 54.1323 29.4645 54.2416 29.3601C54.351 29.2557 54.4124 29.1141 54.4124 28.9665C54.4124 28.8189 54.351 28.6773 54.2416 28.5729C54.1323 28.4685 53.984 28.4099 53.8294 28.4099H28.7985L42.2583 15.5575C42.3393 15.4809 42.3948 15.3831 42.4178 15.2764C42.4409 15.1698 42.4304 15.059 42.3878 14.958C42.3451 14.857 42.2722 14.7703 42.1781 14.7087C42.084 14.6472 41.973 14.6135 41.859 14.612C41.7095 14.6152 41.5668 14.6724 41.4597 14.7721L27.976 27.6283V0.684459C27.9826 0.607744 27.9726 0.530542 27.9464 0.457732C27.9202 0.384922 27.8785 0.318087 27.8239 0.261449C27.7693 0.204811 27.703 0.159601 27.6292 0.128677C27.5554 0.0977535 27.4756 0.0817871 27.395 0.0817871C27.3144 0.0817871 27.2347 0.0977535 27.1608 0.128677C27.087 0.159601 27.0207 0.204811 26.9661 0.261449C26.9115 0.318087 26.8698 0.384922 26.8436 0.457732C26.8175 0.530542 26.8074 0.607744 26.8141 0.684459V27.6245L13.3503 14.7721C13.2442 14.6711 13.1009 14.6136 12.951 14.612C12.8015 14.6152 12.6588 14.6724 12.5518 14.7721C12.4974 14.8235 12.4543 14.8847 12.4249 14.9521C12.3955 15.0195 12.3803 15.0918 12.3803 15.1648C12.3803 15.2378 12.3955 15.3101 12.4249 15.3775C12.4543 15.4449 12.4974 15.5061 12.5518 15.5575L25.9916 28.4099H0.960636C0.806028 28.4099 0.657752 28.4685 0.548428 28.5729C0.439103 28.6773 0.377686 28.8189 0.377686 28.9665C0.377686 29.1141 0.439103 29.2557 0.548428 29.3601C0.657752 29.4645 0.806028 29.5231 0.960636 29.5231Z"
                            fill="white"></path>
                        <g style="mix-blend-mode:soft-light" opacity="0.9">
                            <path
                                d="M0.960636 29.5231H25.9916L12.5278 42.3755C12.4306 42.4818 12.3794 42.6194 12.3844 42.7604C12.3894 42.9014 12.4503 43.0353 12.5548 43.1351C12.6593 43.2349 12.7996 43.293 12.9472 43.2978C13.0949 43.3026 13.239 43.2537 13.3503 43.1609L26.8141 30.3085V57.2485C26.8074 57.3253 26.8175 57.4025 26.8436 57.4753C26.8698 57.5481 26.9115 57.6149 26.9661 57.6715C27.0207 57.7282 27.087 57.7734 27.1608 57.8043C27.2347 57.8352 27.3144 57.8512 27.395 57.8512C27.4756 57.8512 27.5554 57.8352 27.6292 57.8043C27.703 57.7734 27.7693 57.7282 27.8239 57.6715C27.8785 57.6149 27.9202 57.5481 27.9464 57.4753C27.9726 57.4025 27.9826 57.3253 27.976 57.2485V30.3047L41.4357 43.1609C41.5471 43.2537 41.6911 43.3026 41.8388 43.2978C41.9865 43.293 42.1268 43.2349 42.2313 43.1351C42.3357 43.0353 42.3967 42.9014 42.4017 42.7604C42.4067 42.6194 42.3554 42.4818 42.2583 42.3755L28.7985 29.5231H53.8294C53.984 29.5231 54.1323 29.4645 54.2416 29.3601C54.351 29.2557 54.4124 29.1141 54.4124 28.9665C54.4124 28.8189 54.351 28.6773 54.2416 28.5729C54.1323 28.4685 53.984 28.4099 53.8294 28.4099H28.7985L42.2583 15.5575C42.3393 15.4809 42.3948 15.3831 42.4178 15.2764C42.4409 15.1698 42.4304 15.059 42.3878 14.958C42.3451 14.857 42.2722 14.7703 42.1781 14.7087C42.084 14.6472 41.973 14.6135 41.859 14.612C41.7095 14.6152 41.5668 14.6724 41.4597 14.7721L27.976 27.6283V0.684459C27.9826 0.607744 27.9726 0.530542 27.9464 0.457732C27.9202 0.384922 27.8785 0.318087 27.8239 0.261449C27.7693 0.204811 27.703 0.159601 27.6292 0.128677C27.5554 0.0977535 27.4756 0.0817871 27.395 0.0817871C27.3144 0.0817871 27.2347 0.0977535 27.1608 0.128677C27.087 0.159601 27.0207 0.204811 26.9661 0.261449C26.9115 0.318087 26.8698 0.384922 26.8436 0.457732C26.8175 0.530542 26.8074 0.607744 26.8141 0.684459V27.6245L13.3503 14.7721C13.2442 14.6711 13.1009 14.6136 12.951 14.612C12.8015 14.6152 12.6588 14.6724 12.5518 14.7721C12.4974 14.8235 12.4543 14.8847 12.4249 14.9521C12.3955 15.0195 12.3803 15.0918 12.3803 15.1648C12.3803 15.2378 12.3955 15.3101 12.4249 15.3775C12.4543 15.4449 12.4974 15.5061 12.5518 15.5575L25.9916 28.4099H0.960636C0.806028 28.4099 0.657752 28.4685 0.548428 28.5729C0.439103 28.6773 0.377686 28.8189 0.377686 28.9665C0.377686 29.1141 0.439103 29.2557 0.548428 29.3601C0.657752 29.4645 0.806028 29.5231 0.960636 29.5231Z"
                                fill="white"></path>
                        </g>
                    </svg></li>
                <li class="vertical_move"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 18 18" fill="none">
                        <path
                            d="M9.00004 6.62496L2.37499 0L0 2.375L6.62505 8.99996L0 15.625L2.37499 18L9.00004 11.3749L15.625 18L18 15.625L11.375 8.99996L18 2.375L15.625 0L9.00004 6.62496Z"
                            fill="white"></path>
                    </svg></li>
            </ul>
            <img class="bg_img" src="images/Header-Banner-3-1-1.webp" alt="WePro-Solutions" width="1920" height="1000">
            <div class="wrapped">
                <div class="row justify-content-center align-items-start">
                    <div class="col-lg-12  ">
                        <div class="text-col text-white  w-100 ">
                            <h1 class="h2 " data-text="Speech">Speech </h1>
                            <h2 class="h2 text-right" data-aos="fade-right">Publications</h2>
                            <div class="description_box">
                                <h4 class="h4" data-aos="fade-down">Your vision, our expertise</h4>
                                <p class="content mb-md-0 mb-4" data-aos="fade-down">From creating stunning websites
                                    to developing custom software applications, we tailor our solutions to meet your
                                    unique needs.</p>
                            </div>
                        </div>
                    </div>
                    <a class="common_btn mt-4" data-aos="zoom-in" href="{{url('/about')}}" target="_self">Learn More</a>
                </div>
            </div>
        </div>
        
    </div>
</section>
<section class="home-highlights-popular section_with_bg">
    <div class="wrapper">
        <div class="row justify-content-center align-items-start">
            <div class="col-12 mb-4">
                <h2 class="h2 sub_heading primary_text" data-aos="fade-down">Highlights</h2>
        
                <div class="row g-3">
                    @forelse($highlights as $h)
                        <div class="col-md-3" data-aos="fade-up" style="margin-top: 20px;">
                            <div class="card h-100">
                                @if($h->featured_image)
                                    <img src="{{ $h->featured_image }}" class="card-img-top" alt="{{ $h->title }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $h->title }}</h5>
                                    <p class="card-text">{{ Str::limit(strip_tags($h->excerpt ?? $h->description), 120) }}</p>
                                    <a href="{{ url('/news/'.$h->slug) }}" class="btn btn-sm btn-primary">Read</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">No highlights yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="col-12 mt-5">
                <h2 class="h2 sub_heading primary_text" data-aos="fade-down">Popular Books</h2>
                <div class="row g-3">
                    @forelse($popularBooks as $p)
                        <div class="col-12 col-sm-6 col-md-3" data-aos="fade-up" style="margin-top: 20px;">
                            <div class="card card-book h-100 text-center p-2">
                                @if($p->image)
                                    @php
                                        $images = json_decode($p->image, true) ?? [];
                                        if (!empty($images) && isset($images[0])) {
                                            $imgUrl = Storage::disk('s3')->url('product/'.$images[0]);
                                        } else {
                                            $imgUrl = asset('images/no-image.png');
                                        }
                                    @endphp
                                    <a href="{{ url('/book-details/'.$p->slug) }}"><img src="{{ $imgUrl }}" alt="{{ $p->name }}" class="img-fluid mb-2 book-thumb"></a>
                                @endif
                                <h6 class="mb-1"><a href="{{ url('/book-details/'.$p->slug) }}">{{ $p->name }}</a></h6>
                                <div class="text-muted">₹{{ number_format($p->price,2) }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">No popular books yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<div class="vision_mission ">
        <div class="wrapper position-relative">
            <div class="row">
                <div class="col-md-4 mb-md-0 mb-5">
                    <div class="vision_item w-100 h-100" data-aos="fade-down">
                        <figure class="text-center"><img src="images/Our-Mission-1-1-1.png" alt="Our Mission"
                                width="232" height="232">
                        </figure>
                        <h4 class="h4 primary_text text-center">Our Mission</h4>
                        <p class="text-center">The mission of Speech Publications is to promote a culture of meaningful
                            dialogue between readers and writers by publishing high-quality content in literature,
                            education, research, thought, and creative expressions such as poetry.  </p>
                    </div>
                </div>
                <div class="col-md-4 mb-md-0 mb-5">
                    <div class="vision_item w-100 h-100" data-aos="fade-down">
                        <figure class="text-center"><img src="images/Our-Vision-2-1-1.png" alt="Our Vision" width="232"
                                height="232">
                        </figure>
                        <h4 class="h4 primary_text text-center">Our Vision</h4>
                        <p class="text-center">The vision of Speech Publications is to help build a society where
                            literature, thought, and creativity are encouraged, and where awareness, sensitivity, and
                            positive transformation are made possible through the power of words. </p>
                    </div>
                </div>
                <div class="col-md-4 mb-md-0 mb-5">
                    <div class="vision_item w-100 h-100" data-aos="fade-down">
                        <figure class="text-center"><img src="images/Our-Values-2-1.png" alt="Our Values" width="232"
                                height="232">
                        </figure>
                        <h4 class="h4 primary_text text-center">Our Values</h4>
                        <p class="text-center">At Speech Publications, we are guided by the following core values that
                            define our mission, vision, and every step of our publishing journey</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<section class="review_sec position-relative  section_with_bg ">
    <img class="bg_img" src="images/Testimonial-Background-Elements-1.png" alt="WePro-Solutions" width="1830"
        height="533">
    <div class="wrapper">
        <div class="intro_box">
            <h6 class="h6 sub_heading   mb-lg-4 mb-3 primary_text" data-aos="fade-down">why choose us</h6>
            <h2 class="h2 primary_text " data-aos="fade-left">What Clients are Saying</h2>
        </div>
        <div class="review_slider" data-aos="fade-down">
            @foreach($testimonials as $testimonial)
            <div class="review_item position-relative">
                <div class="heading_box d-flex">
                    <figure><img src="{{$testimonial->image}}" alt="Zandy Willems" width="101" height="101">
                    </figure>
                    <div class="title">
                        <span class="quote_img"><svg xmlns="http://www.w3.org/2000/svg" width="51" height="36"
                                viewBox="0 0 51 36" fill="none">
                                <path
                                    d="M0.109375 0V35.2935L18.8399 17.6468V0H0.109375ZM31.3269 0V35.2935L50.0573 17.6468V0H31.3269Z"
                                    fill="#A1BEFF"></path>
                            </svg></span>
                        <h6 class="h6 mb-2">{{$testimonial->name}}
 
</h6>
                        <p class="mb-2">{{$testimonial->designation}}</p>
                        <div class="rating">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17"
                                fill="none">
                                <path
                                    d="M9.00008 0.666748L11.5751 5.88341L17.3334 6.72508L13.1667 10.7834L14.1501 16.5167L9.00008 13.8084L3.85008 16.5167L4.83342 10.7834L0.666748 6.72508L6.42508 5.88341L9.00008 0.666748Z"
                                    fill="#2c5ac3"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17"
                                fill="none">
                                <path
                                    d="M9.00008 0.666748L11.5751 5.88341L17.3334 6.72508L13.1667 10.7834L14.1501 16.5167L9.00008 13.8084L3.85008 16.5167L4.83342 10.7834L0.666748 6.72508L6.42508 5.88341L9.00008 0.666748Z"
                                    fill="#2c5ac3"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17"
                                fill="none">
                                <path
                                    d="M9.00008 0.666748L11.5751 5.88341L17.3334 6.72508L13.1667 10.7834L14.1501 16.5167L9.00008 13.8084L3.85008 16.5167L4.83342 10.7834L0.666748 6.72508L6.42508 5.88341L9.00008 0.666748Z"
                                    fill="#2c5ac3"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17"
                                fill="none">
                                <path
                                    d="M9.00008 0.666748L11.5751 5.88341L17.3334 6.72508L13.1667 10.7834L14.1501 16.5167L9.00008 13.8084L3.85008 16.5167L4.83342 10.7834L0.666748 6.72508L6.42508 5.88341L9.00008 0.666748Z"
                                    fill="#2c5ac3"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17"
                                fill="none">
                                <path
                                    d="M9.00008 0.666748L11.5751 5.88341L17.3334 6.72508L13.1667 10.7834L14.1501 16.5167L9.00008 13.8084L3.85008 16.5167L4.83342 10.7834L0.666748 6.72508L6.42508 5.88341L9.00008 0.666748Z"
                                    fill="#2c5ac3"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="content" id="content-{{ $loop->index }}">
                    <br>
                    <p>{{$testimonial->description}}</p>
                </div>
                <div class="card-footer text-center">
                <button class="btn btn-primary" onclick="const content = document.getElementById('content-{{ $loop->index }}'); content.classList.toggle('show'); this.textContent = content.classList.contains('show') ? 'कम दिखाएं' : 'और पढ़ें'">
                    और पढ़ें
                </button>
            </div>
            </div>
          
            @endforeach
        </div>
    </div>
</section>

 
@endsection