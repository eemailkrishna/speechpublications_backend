var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */
        this.classList.toggle("active");

        /* Toggle between hiding and showing the active panel */
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}


document.addEventListener("mousemove", e => {
    AOS.init({
        offset: 0, duration: 1000, disable: function () {
            return window.innerWidth < 1199; // Disable AOS on screens smaller than 768px
        }
    });
    AOS.refresh();
});
jQuery(document).ready(function ($) {


    AOS.init({
        offset: 0, duration: 1000, disable: function () {
            return window.innerWidth < 1199; // Disable AOS on screens smaller than 768px
        }
    });
    $(window).bind('scroll', function () {
        var sticky = $(window).scrollTop();

//  	alert(sticky);

        if (sticky >= 10) {
            $('header.main_header').addClass('top_most');
        } else {
            $('header.main_header').removeClass('top_most');
        }
    });

    $('header .mob_nav').click(function (e) {
        e.preventDefault();
        $('header .mob_nav').toggleClass('active');
        $('header nav').toggleClass('active');
        $('body').toggleClass('menu_open');
    });

    jQuery('.menu-item-has-children> a').after("<span class='sidebar-menu-arrow'></span>");

    jQuery('.sidebar-menu-arrow').click(function () {
        jQuery(this).toggleClass('active');
        jQuery(this).next('.sub-menu').slideUp(300);
        if (jQuery(this).next().is(':visible')) {
            jQuery(this).next().slideUp(300);
        } else {
            jQuery(this).next().slideDown(300);
        }


    });

    $('.searchform #s').attr('placeholder', 'Search');


    /*====== Header Search Popup =======*/
    jQuery('header #global-search').click(function () {
        jQuery('body').addClass('menu_open');
        jQuery('.search-panel').addClass('search_open');
        // jQuery('.search-panel').css('transform', 'translateY(0)');
        jQuery("#searchform_header").find('input:text').val('');

//alert('fgf');


    });


    jQuery('.search_form .search-close ').click(function () {
        jQuery('body').removeClass('menu_open');
        //  jQuery('.search-panel').css('transform', 'translateY(-100%)');
        jQuery('.search-panel').removeClass('search_open');
        jQuery("#searchform_header").find('input:text').val('');
        // alert('fgf');
    });


    /* Blog Sidebar Search Error Message */
    jQuery('<p class="msg-side-form d-none">Enter Search Key</p>').insertAfter('#searchform_header input[type="text"]');
    jQuery('<p class="msg-side-form d-none">Enter Search Key</p>').insertAfter('#blog-searchform input[type="text"]');
    jQuery("#searchform_header .search-btn").click(function () {
        var searchinput_val = jQuery('#searchform_header input[type="text"]').val();
        console.log(searchinput_val);
        if (searchinput_val == '') {
            jQuery(this).parents('form').find(".msg-side-form").removeClass('d-none');
        } else {
            jQuery(this).parents('form').find(".msg-side-form").addClass('d-none');
        }
    });

    jQuery("#searchform_header input[type='text']").focus(function () {
        jQuery(this).parents('form').find(".msg-side-form").addClass('d-none');
    });


    jQuery('#searchform_header input[type="text"]').bind('invalid', function () {
        return false;
    });


    (function () {
        var fonts = document.createElement('link');
        fonts.href = 'https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap';
        fonts.rel = 'stylesheet';
        fonts.type = 'text/css';
        document.getElementsByTagName('head')[0].appendChild(fonts);

    })();

    $('.slider_box').slick({
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 300,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        pauseOnHover: false,
    });

    $('.review_slider').slick({
        infinite: true,
        speed: 300,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 2,
        slidesToScroll: 1,
        centerMode: false,
        dots: false,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                }
            },]

    });
    $('.project_slider').slick({
        infinite: true,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        pauseOnHover: false,
        centerMode: true,
        centerPadding: '200px',
        dots: false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 1,
                    centerPadding: '150px',
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 1,
                    centerPadding: '100px',
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    centerPadding: '40px',
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
});
jQuery(document).ready(function () {

    //var slidesCount = jQuery('.banner_slider .slide').length;
    var slidesCount = jQuery('.banner_slider .slick-slide:not(.slick-cloned)').length; // Exclude cloned slides

    if (parseInt(slidesCount) < 10) {
        jQuery('.total_slide').html('0' + slidesCount);
    } else {

        jQuery('.total_slide').html(slidesCount);
    }

    countSlide();
    jQuery('.banner_slider .slider_box').on('afterChange', function (event, slick, currentSlide, nextSlide) {
        countSlide();
    });

    //jQuery('.current_slide').html('01');
    function countSlide() {
        var current = jQuery('.banner_slider .slider_box').find('.slick-current').attr('data-slick-index');
        var current_slide = parseInt(Number(current)) + 1;
        if (parseInt(current_slide) < 10) {
            jQuery('.current_slide').html('0' + current_slide);
        } else {
            jQuery('.current_slide').html(current_slide);
        }
    }
});
