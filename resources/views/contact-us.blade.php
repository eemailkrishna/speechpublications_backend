
@extends('layouts.app')
@section('content')
    <article class="contact_page ">
        <div class="inner_banner"
            style="background:#003366">
            <div class="wrapper">
                <h1 class=" mb-0 h2 text-white text-center" data-aos="fade-down">Contact Us</h1>
                <!-- 	<ul class="breadcrumb">
			<li>Home</li>
		</ul> -->
            </div>

        </div>

        <section class="form_section section_with_bg mb-3">
            <div class="wrapped">
                <div class="row flex-row-reverse justify-content-center align-items-center">
                    <div class="col-lg-6 mb-lg-0 mb-4 ">
                        <div class="text-col w-100  text-md-left text-center ">
                            <h2 class="h2  mb-lg-4 mb-3 primary_text" data-aos="fade-down">We’re Here to help you!</h2>
                            <div class="info " data-aos="fade-down">Have a question for our team? Whether you’re
                                interested in working with us, need an expert opinion — we’d love to hear from you.
                            </div>
                            <div class="form_box mt-md-5 mt-4" data-aos="fade-left">
                                <h5 class="primary_text mb-lg-4 mb-3 pb-3">Get In touch</h5>
                                <div class="wpcf7 no-js" id="wpcf7-f354-o1" lang="en" dir="ltr" data-wpcf7-id="354">
                                    <div class="screen-reader-response">
                                        <p role="status" aria-live="polite" aria-atomic="true"></p>
                                        <ul></ul>
                                    </div>
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



                                    
                                    <form  id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="contact-form" aria-label="Contact form">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <input type="text" name="fullname" placeholder="Name" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <input type="email" name="email" placeholder="Email Address" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <input type="tel" name="phone" placeholder="Phone" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <input type="text" name="subject" placeholder="Subject" class="form-control" required>
        </div>

        <div class="col-12 mb-4">
            <textarea name="message" placeholder="Write Message..." class="form-control" rows="4" required></textarea>
        </div>

        <div class="col-12 d-flex justify-content-center">
            <button type="submit" id="submitBtn" class="wpcf7-form-control has-spinner common_btn">
                <span id="btnText">CONTACT US TODAY!</span>
                <span id="btnLoader" class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status"></span>
            </button>
        </div>
    </div>
</form>

                                      
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-lg-0 mt-4">
                        <div class="img-col position-relative  text-center" data-aos="fade-right">
                            <img src="{{url("public/images/logo/Logo2.webp")}}"
                                alt="" width="585" height="617">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="map_section position-relative section_with_bg" data-aos="fade-left">
            <div class="wrapper position-relative">
                <div class="contact_info" data-aos="fade-right" data-aos-delay="500">
                    <h5 class="text-white mb-lg-4 mb-3 ">Contact Info</h5>
                    <div class="info_box address">
                        <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                viewBox="0 0 38 38" fill="none">
                                <circle opacity="0.1" cx="19" cy="19" r="19" fill="white" />
                                <g clip-path="url(#clip0_543_458)">
                                    <path
                                        d="M19 10C17.2105 10.0024 15.495 10.7143 14.2297 11.9797C12.9643 13.245 12.2524 14.9605 12.25 16.75C12.25 21.5959 18.5387 27.6653 18.8059 27.9212C18.8579 27.9718 18.9275 28 19 28C19.0725 28 19.1421 27.9718 19.1941 27.9212C19.4612 27.6653 25.75 21.5959 25.75 16.75C25.7476 14.9605 25.0357 13.245 23.7703 11.9797C22.505 10.7143 20.7895 10.0024 19 10ZM19 19.8438C18.3881 19.8438 17.79 19.6623 17.2812 19.3224C16.7724 18.9824 16.3759 18.4992 16.1417 17.9339C15.9076 17.3686 15.8463 16.7466 15.9657 16.1464C16.0851 15.5463 16.3797 14.9951 16.8124 14.5624C17.2451 14.1297 17.7963 13.8351 18.3964 13.7157C18.9966 13.5963 19.6186 13.6576 20.1839 13.8917C20.7492 14.1259 21.2324 14.5224 21.5724 15.0312C21.9123 15.54 22.0938 16.1381 22.0938 16.75C22.0933 17.5704 21.7672 18.357 21.1871 18.9371C20.607 19.5172 19.8204 19.8433 19 19.8438Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_543_458">
                                        <rect width="18" height="18" fill="white" transform="translate(10 10)" />
                                    </clipPath>
                                </defs>
                            </svg></div>
                        <div class="info text-white">In Front Street of Shibli National Inter College,
Pandey Bazar, Azamgarh (U.P.)276001</div>
                    </div>
                    <div class="info_box address">
                        <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                viewBox="0 0 38 38" fill="none">
                                <circle opacity="0.1" cx="19" cy="19" r="19" fill="white" />
                                <path
                                    d="M27.1469 23.4963C27.1138 23.4688 23.3663 20.8025 22.3544 20.9656C21.8663 21.0519 21.5875 21.385 21.0281 22.0512C20.8735 22.2367 20.7153 22.4193 20.5538 22.5988C20.2002 22.4836 19.8554 22.3432 19.5219 22.1788C17.8003 21.3406 16.4094 19.9497 15.5712 18.2281C15.4068 17.8946 15.2664 17.5498 15.1512 17.1962C15.335 17.0281 15.5925 16.8113 15.7025 16.7188C16.3656 16.1625 16.6981 15.8831 16.7844 15.3944C16.9612 14.3825 14.2812 10.6363 14.2537 10.6025C14.1317 10.4294 13.9727 10.2856 13.7884 10.1814C13.604 10.0772 13.3987 10.0152 13.1875 10C12.1012 10 9 14.0225 9 14.7006C9 14.74 9.05688 18.7425 13.9925 23.7631C19.0075 28.6931 23.01 28.75 23.0494 28.75C23.7269 28.75 27.75 25.6487 27.75 24.5625C27.7346 24.3512 27.6725 24.146 27.5682 23.9616C27.4639 23.7772 27.32 23.6183 27.1469 23.4963ZM22.98 27.4963C22.4375 27.45 19.075 27.0069 14.875 22.8813C10.7294 18.6606 10.2975 15.2925 10.2544 14.7706C11.0736 13.4848 12.063 12.3156 13.1956 11.295C13.2206 11.32 13.2537 11.3575 13.2962 11.4062C14.1649 12.592 14.9132 13.8614 15.53 15.1956C15.3294 15.3974 15.1174 15.5875 14.895 15.765C14.5501 16.0278 14.2334 16.3256 13.95 16.6537C13.902 16.721 13.8679 16.7972 13.8496 16.8777C13.8312 16.9583 13.829 17.0417 13.8431 17.1231C13.9754 17.6961 14.178 18.2505 14.4462 18.7738C15.4074 20.7475 17.0024 22.3422 18.9762 23.3031C19.4994 23.5718 20.0538 23.7746 20.6269 23.9069C20.7083 23.9213 20.7918 23.9193 20.8724 23.9009C20.953 23.8825 21.0291 23.8482 21.0963 23.8C21.4255 23.5154 21.7244 23.1975 21.9881 22.8512C22.1844 22.6175 22.4462 22.3056 22.5456 22.2175C23.8832 22.8337 25.1555 23.5829 26.3431 24.4537C26.395 24.4975 26.4319 24.5312 26.4562 24.5531C25.4356 25.6861 24.2662 26.6757 22.98 27.495V27.4963ZM22.75 18.75H24C23.9985 17.4244 23.4712 16.1535 22.5339 15.2161C21.5965 14.2788 20.3256 13.7515 19 13.75V15C19.9943 15.001 20.9475 15.3964 21.6506 16.0994C22.3536 16.8025 22.749 17.7557 22.75 18.75Z"
                                    fill="white" />
                                <path
                                    d="M25.875 18.75H27.125C27.1225 16.5959 26.2657 14.5307 24.7425 13.0075C23.2193 11.4843 21.1541 10.6275 19 10.625V11.875C20.8227 11.8772 22.5701 12.6022 23.859 13.891C25.1478 15.1799 25.8728 16.9273 25.875 18.75Z"
                                    fill="white" />
                            </svg></div>
                        <div class="info text-white"><a href="tel:+919278199961">9278199961</a></div>
                    </div>
                    <div class="info_box email">
                        <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="38" height="38"
                                viewBox="0 0 38 38" fill="none">
                                <circle opacity="0.1" cx="19" cy="19" r="19" fill="white" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M27.3434 22.6307C27.3434 24.3196 25.9696 25.6912 24.2829 25.6912H14.2416C12.5548 25.6912 11.1809 24.3195 11.1809 22.6307V16.2415C11.1806 15.6972 11.3264 15.1628 11.6032 14.6941L16.5027 19.5936C17.2357 20.3288 18.2173 20.7338 19.2632 20.7338C20.307 20.7338 21.2886 20.3288 22.0216 19.5936L26.9212 14.6941C27.198 15.1628 27.3438 15.6972 27.3434 16.2415V22.6307H27.3434ZM24.2828 13.1809H14.2416C13.545 13.1809 12.902 13.4167 12.3876 13.8089L17.3364 18.7599C17.8487 19.27 18.5324 19.5529 19.2632 19.5529C19.992 19.5529 20.6757 19.27 21.1879 18.7599L26.1367 13.8089C25.6224 13.4167 24.9794 13.1809 24.2828 13.1809ZM24.2828 12H14.2416C11.9032 12 10 13.9032 10 16.2416V22.6307C10 24.9712 11.9032 26.8723 14.2416 26.8723H24.2828C26.6211 26.8723 28.5244 24.9712 28.5244 22.6307V16.2415C28.5244 13.9032 26.6211 12 24.2828 12Z"
                                    fill="white" />
                            </svg></div>
                        <div class="info text-white"><a
                                href="mailto:speechpublications@gmail.com
">speechpublications@gmail.com
</a></div>
                    </div>
                </div>
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d895.9389411261335!2d83.18204826955224!3d26.07423720318683!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMjbCsDA0JzI3LjMiTiA4M8KwMTAnNTcuNyJF!5e0!3m2!1sen!2sin!4v1757264161328!5m2!1sen!2sin" 
                width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
                
        </section>
    </article>
<script>
    setTimeout(function(){
        let alert = document.querySelector('.alert-success');
        if(alert){
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000); // 5 seconds
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("contactForm");
    const submitBtn = document.getElementById("submitBtn");
    const btnText = document.getElementById("btnText");
    const btnLoader = document.getElementById("btnLoader");

    form.addEventListener("submit", function() {
        // Disable button and show loader
        submitBtn.disabled = true;
        btnText.textContent = "Sending...";
        btnLoader.classList.remove("d-none");
    });
});
</script>


   

  @endsection