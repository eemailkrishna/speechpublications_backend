@extends('layouts.app')
@section('content')
<article class="about_page  ">
    <div class="inner_banner"
    style="background:#003366"     >
        <div class="wrapper " style="margin-bottom:80px">
            <h1 class=" mb-0 h2 text-white text-center" data-aos="fade-down">About Us</h1>
          
        </div>

    </div>



    <section class="about_section section_with_bg ">
        <div class="wrapper" style="margin-bottom:80px">
            <div class="row  justify-content-center align-items-center">
                <div class="col-lg-6 mb-lg-0 mb-4 ">
                    <div class="text-col w-100  ">
                        <h6 class="h6 sub_heading  mb-lg-4 mb-3 primary_text" data-aos="fade-down">About Us</h6>
                        <h2 class="h2 mb-4 primary_text" data-aos="fade-up">Speech Publications </h2>
                        <div class="content " data-aos="fade-up">
                            <p>Speech Publications is a leading publishing institution dedicated to literary expression,
                                intellectual discourse, and social awareness. This organization not only promotes
                                contemporary literature and ideological discussions but also publishes high-quality
                                books across a diverse range of subjects including education, research, sociology,
                                lifestyle, psychology, history, and children's literature.
                            </p>
                            <p class="mt-2">We firmly believe that writing is not merely an embellishment of words, but
                                a powerful medium to awaken and sensitize society. With this belief, we provide a
                                platform for both emerging and established authors to not only share their creativity
                                but also present ideas that can inspire and impact society in a meaningful way.
                               
                               
                            </p>
                             <p class="mt-2">
                                The core objective of Speech Publications is to create a strong intellectual bridge
                                between readers and writers, focusing on readability, authenticity, and quality. We
                                believe that creative literature and thoughtful writing can lay the foundation for
                                social change, and we are constantly working in this direction.
                                </p>
                            <p class="mt-2">Every book we publish is the beginning of a dialogue — a philosophy that
                                connects the thoughts of the reader with the emotions of the writer.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="img-col position-relative  text-center" data-aos="fade-left">
                        <img src="{{url("public/images/logo/Logo2.webp")}}" alt=""
                            width="718" height="716">
                    </div>
                </div>
            </div>
        </div>
    </section>
   



 

    <script type="text/javascript">
    jQuery(document).ajaxComplete(function() {
        jQuery(".html5lightbox").html5lightbox();
    });
    </script>
  




</article>



@endsection