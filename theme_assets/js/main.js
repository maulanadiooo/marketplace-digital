/*
    Name: Digipro - Digital Products Marketplace
    Author: AazzTech
    Version: 1.0
*/

(function () {
    "use strict";

    var windowWidth = $(window).width();
    var windowHeight = $(window).height();

    // Custom nav trigger function for owl carousel
    function customTrigger(slideNext, slidePrev, targetSlider) {
        $(slideNext).on('click', function () {
            targetSlider.trigger('next.owl.carousel');
        });
        $(slidePrev).on('click', function () {
            targetSlider.trigger('prev.owl.carousel');
        });
    }

    // Mobile Menu JS
    function mobileMenu(triggerElem, dropdown) {
        var $dropDownTrigger = $(triggerElem + ' > a');

        $dropDownTrigger.append('<span class="icon-plus"></span>');
        $dropDownTrigger.on('click', function (e) {
            e.preventDefault();
            $(this).parents(triggerElem).find(dropdown).slideToggle().parents(triggerElem).siblings().find(dropdown).slideUp();
        });
    }

    if (windowWidth < 991) {
        mobileMenu('.has_dropdown', '.dropdown');
        mobileMenu('.has_megamenu', '.dropdown_megamenu');
    }


    // Header Search
    $('.search_trigger').on('click', function () {
        $(this).toggleClass('icon-magnifier icon-close');
        $(this).parent('.search_module').children('.search_area').toggleClass('active');
    });

    // Offcanvas Menu
    $('.close_menu').on('click', function () {
        $(this).parent('.offcanvas-menu').addClass('closed');
    });

    $('.menu_icon').on('click', function () {
        $(this).siblings('.offcanvas-menu').removeClass('closed');
    });

    // Filter menu reveal on click
    $('.filter__menu_icon').on('click', function () {
        $('.filter_dropdown').toggleClass('active');
    });

    // Click event to scroll to top
    /* go top */
    var scrollTop = $('.go_top').hide();

    $(window).on('scroll', function () {
        var distanceFromTop = $(document).scrollTop();
        if (distanceFromTop > 117) {
            scrollTop.fadeIn(400);
        }
        else {
            scrollTop.fadeOut(400);
        }
    });
    scrollTop.on('click', function () {
        $('html, body').animate({scrollTop: 0}, 800);
        return false;
    });

    // Setting background images
    $('.bg_image_holder').each(function () {
        var $this = $(this);
        var imgLink = $this.children().attr('src');
        $this.css({
            "background-image": "url(" + imgLink + ")",
            "opacity": "1"
        }).children().attr('alt', imgLink);
    });

    // Counter Up
    $('.count_up').counterUp({
        delay: 10,
        time: 1000
    });

    // jquery ui range
    var $priceFrom = $('.price-ranges .from'),
        $priceTo = $('.price-ranges .to');
    $(".price-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [30, 300],
        slide: function (event, ui) {
            $priceFrom.text("$" + ui.values[0]);
            $priceTo.text("$" + ui.values[1]);
        }
    });

    // Venobox
    if ($.prototype.venobox) {
        $('.venobox').venobox();
    }

    // Product page edit option js
    $(".prod_option .setting-icon").on('click', function () {
        $(this).siblings('.options').toggle();
    });

    // Reply comment area js goes here
    var $replyForm = $('.reply-comment'),
        $replylink = $('.reply-link');
    $replyForm.hide();

    $replylink.on('click', function (e) {
        e.preventDefault();
        $(this).parents('.media').siblings('.reply-comment').toggle().find('textarea').focus();
    });

    // Countdown Init
    $('.countdown').countdown('2021/6/18', function (event) {
        var $this = $(this).html(event.strftime(''
            + '<li>%D <span>days</span></li>  '
            + '<li>%H <span>hours</span></li>  '
            + '<li>%M <span>minutes</span></li>  '
            + '<li>%S <span>seconeds</span></li> '));
    });

    // Accordion JS
    var $accordionTrigger = $('.single_acco_title a');
    $accordionTrigger.on('click', function () {
        $accordionTrigger.not(this).removeClass('active').find('.lnr').not($(this).find('.lnr')).removeClass('icon-arrow-down-circle').addClass('icon-arrow-right-circle');
        $(this).toggleClass('active').find('.lnr').toggleClass('icon-arrow-right-circle icon-arrow-down-circle');
    });

    // Date Picker JS
    $('.dattaPikkara').datepicker();

    // Price Selection JS
    var $licenseText = $('.card--pricing2 .pricing-options li p'),
        $price = $('.card--pricing2 .price h1 span');
    $licenseText.slideUp();

    $('.card--pricing2 .custom-radio label').on('click', function () {
        var $this = $(this);
        $licenseText.slideUp(200);
        $this.parents('li').find('p').slideDown(200);
        $price.text($this.data('price') + '.00');
    });

    /*
        Removing extra margin from
        the last child of
        item description-area
    */
    $('.tab-content-wrapper').length ? $('#product-details').children().children().last().css({
        'margin-bottom': 0,
        'padding-bottom': 0
    }) : $('#product-details').children().last().css({'margin-bottom': 0, 'padding-bottom': 0});

    // Add Credit Page JS
    var $amount = $('.amounts ul li');
    $amount.on('click', function () {
        $(this).find('p').addClass('selected');
        $(this).siblings($amount).find('p').removeClass('selected');
        $('.selected_price').val($(this).data('price'));
    });

    // Setting Files Name
    $('.attachment_field').on('change', function (e) {
        var files = e.target.files;
        var attached = $('.attached');
        for (var i = 0; files.length > i; i++) {
            attached.append('<p>' + files[i].name + '<span class="icon-close"></span></p>');
        }
    });

    // Starring
    var starring = $('.actions span.fa');
    starring.on('click', function () {
        $(this).toggleClass('fa-star-o fa-star');
    });

    // Remove Uploaded files name when clicked on Cross
    $('.attached').on('click', 'p>span', function () {
        $(this).parent().remove();
    });

    // Followers Following JS
    $('.user--following .btn').on('mouseenter', function () {
        $(this).text('unfollow');
    }).on('mouseleave', function () {
        $(this).text('following');
    });

    // Bar Rating Plugin Installation
    $('.give_rating').barrating({
        theme: 'fontawesome-stars'
    });


    // Custom Slick Slider Navigation
    function slickCustomTrigger(slider, prev, next) {
        prev.on('click', function () {
            slider.slick('slickNext');
        });
        next.on('click', function () {
            slider.slick('slickPrev');
        });
    }

    // Featured Product Slider
    var $featuredProd = $('.prod-slider1');
    $featuredProd.owlCarousel({
        items: 1,
        autoplay: false
    });
    customTrigger('.product__slider-nav .nav_right', '.product__slider-nav .nav_left', $featuredProd);

    var $featuredProd2 = $('.prod-slider2');
    $featuredProd2.owlCarousel({
        items: 1,
        autoplay: false
    });
    customTrigger('.prod_slide_prev', '.prod_slide_next', $featuredProd2);

    // Testimonial Slider
    $('.testimonial-slider').owlCarousel({
        items: 1,
        dots: false,
        nav: true,
        navText: ["<i class='icon-arrow-left'></i>", "<i class='icon-arrow-right'></i>"],
        responsive: {
            0: {
                items: 1,
                nav: false
            },
            480: {
                items: 1,
                nav: false
            },
            768: {
                items: 1
            },
            992: {
                items: 1
            }
        }
    });

    // Sponsors Slider
    $('.sponsores').owlCarousel({
        items: 4,
        autoplay: true,
        margin: 30,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 4
            }
        }
    });

    // Newest Product Slider
    var productSlider = $('.product_slider');
    productSlider.owlCarousel({
        items: 3,
        margin: 30,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            }
        }
    });
    customTrigger('.follow_feed_nav .nav_right', '.follow_feed_nav .nav_left', productSlider);

    // Partners Slider
    $('.partners').owlCarousel({
        items: 5,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            768: {
                items: 3
            },
            992: {
                items: 5
            }
        }
    });

    // This is product preview slider
    $('.item__preview-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.thumb-slider'
    });

    var thumbSlider = $('.thumb-slider');
    thumbSlider.slick({
        slidesToShow: 7,
        slidesToScroll: 1,
        arrows: false,
        focusOnSelect: false,
        asNavFor: '.item__preview-slider',
        responsive: [
            {
                breakpoint: 479,
                settings: {
                    slidesToShow: 3
                }
            }
        ]
    });

    // Assign custom trigger for thumb-Slider
    slickCustomTrigger(thumbSlider, $('.thumb-nav .nav-left'), $('.thumb-nav .nav-right'));

    // Dropdown JS
    $('.dropdown-trigger').on('click', function (e) {
        e.preventDefault();
        var dropdown = $(this).siblings('.dropdown');
        dropdown.toggleClass('active');
        $('.dropdown').not(dropdown).removeClass('active');
    });

    // Trumbowyg Init
    if ($('#trumbowyg-demo').length) {
        $('#trumbowyg-demo').trumbowyg();
    }

    // Tooltip Trigger
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Replace all SVG images with inline SVG
    $('img.svg').each(function () {
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        $.get(imgURL, function (data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });

    // Featured Product Slider
    $(".product-slide-area").owlCarousel({
        items: 2,
        margin: 30,
        dots: false,
        nav: true,
        navText: ["<i class='icon-arrow-left'></i>", "<i class='icon-arrow-right'></i>"],
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 2
            }
        }
    });

    // Testimonial Carousel (Slick)
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: "<span class='slick-prev'><i class='icon-arrow-left'></i></span>",
        nextArrow: "<span class='slick-next'><i class='icon-arrow-right'></i></span>",
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        centerMode: true,
        focusOnSelect: true,
        variableWidth: false,
        arrows: false,
        centerPadding: 15,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3
                }
            }
        ]
    });


    // Select2 Triggers
    $(".select2_default").select2({
        placeholder: "Multiple Select",
        width: "100%",
        containerCssClass: "form-control"
    });

    $(".select2_tagged").select2({
        multiple: true,
        placeholder: "Select options",
        containerCssClass: "form-control"
    });

    // Custom Checkbox
    $(".custom_checkbox .slider").on("click", function () {
        var el = $(this).parents(".custom_checkbox").children(".check-confirm");
        el.text() === el.data("text-swap")
            ? el.text(el.data("text-original"))
            : el.text(el.data("text-swap"));
    });

    // Dashboard menu toggle on small devices
    $(".menu-toggler").on("click", function () {
        $(".dashboard_menu").toggleClass("active");
    });

    //remove preload after window load
    $(window).load(function () {
        $("body").removeClass("preload");
    });

    //author social icon break-fix
    var lis = $(".author-profile .author-social ul li");
    if(lis.length >= 4){
        lis.addClass("split");
    };

    //withdrew partial amount - dashboard-withdraw
    var p_amount = $("#partial_amount");
    p_amount.hide();
    $('input[name="filter_opt"]').on("change", function () {
        if($("input#opt5").is(":checked")){
            p_amount.show();
        }else{
            p_amount.hide();
        }
    });

    //Video Popup
    $('.video-iframe').magnificPopup({
        type: 'iframe',
        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
            '<div class="mfp-close"></div>' +
            '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
            '</div>',
            patterns: {
                youtube: {
                    index: 'youtube.com/',
                    id: function(url) {
                        var m = url.match(/[\\?\\&]v=([^\\?\\&]+)/);
                        if ( !m || !m[1] ) return null;
                        return m[1];
                    },
                    src: '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
                },
                vimeo: {
                    index: 'vimeo.com/',
                    id: function(url) {
                        var m = url.match(/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/);
                        if ( !m || !m[5] ) return null;
                        return m[5];
                    },
                    src: '//player.vimeo.com/video/%id%?autoplay=1'
                }
            },
            srcAction: 'iframe_src'
        },
        mainClass: 'mfp-fade'
    });

})();




