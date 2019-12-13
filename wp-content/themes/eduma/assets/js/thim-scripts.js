/*
* Re-structure JS
* */
(function($) {
    'use strict';

    /*
    * Helper vars
    * */

    /*
    * Helper functions
    * */
    function thim_get_url_parameters(sParam) {
        var sPageURL = window.location.search.substring(1);

        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1];
            }
        }

    }

    var thim_eduma = {
        ready: function() {
            this.register_ajax();
            this.login_ajax();
            this.login_form_popup();
            this.form_submission_validate();
            this.thim_TopHeader();
            this.ctf7_input_effect();
            this.thim_course_filter();
            this.mobile_menu_toggle();
        },

        load: function() {
            this.thim_menu();
            this.thim_carousel();
            this.thim_contentslider();
            this.counter_box();
        },

        resize: function() {

        },

        validate_form: function(form) {
            var valid = true,
                email_valid = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

            form.find('input.required').each(function() {
                // Check empty value
                if (!$(this).val()) {
                    $(this).addClass('invalid');
                    valid = false;
                }

                // Uncheck
                if ($(this).is(':checkbox') && !$(this).is(':checked')) {
                    $(this).addClass('invalid');
                    valid = false;
                }

                // Check email format
                if ('email' === $(this).attr('type')) {
                    if (!email_valid.test($(this).val())) {
                        $(this).addClass('invalid');
                        valid = false;
                    }
                }

                // Check captcha
                if ($(this).hasClass('captcha-result')) {
                    let captcha_1 = parseInt($(this).data('captcha1')),
                        captcha_2 = parseInt($(this).data('captcha2'));

                    if ((captcha_1 + captcha_2) !== parseInt($(this).val())) {
                        $(this).addClass('invalid').val('');
                        valid = false;
                    }
                }
            });

            // Check repeat password
            if (form.hasClass('auto_login')) {
                let $pw = form.find('input[name=password]'),
                    $repeat_pw = form.find('input[name=repeat_password]');

                if ($pw.val() !== $repeat_pw.val()) {
                    $pw.addClass('invalid');
                    $repeat_pw.addClass('invalid');
                    valid = false;
                }
            }

            $('form input.required').on('focus', function() {
                $(this).removeClass('invalid');
            });

            return valid;
        },

        login_form_popup: function() {
            $(document).on('click', 'body:not(".loggen-in") .thim-button-checkout',
                function(e) {
                    if ($(window).width() > 767) {
                        e.preventDefault();
                        if ($('#thim-popup-login').length) {
                            $('body').addClass('thim-popup-active');
                            $('#thim-popup-login').addClass('active');
                        } else {
                            var redirect = $(this).data('redirect');
                            window.location = redirect;
                        }
                    } else {
                        e.preventDefault();
                        var redirect = $(this).data('redirect');
                        window.location = redirect;
                    }
                });

            $(document).on('click', '#thim-popup-login .close-popup', function(event) {
                event.preventDefault();
                $('body').removeClass('thim-popup-active');
                $('#thim-popup-login').removeClass();
            });

            $('body .thim-login-popup a.js-show-popup').on('click', function(event) {
                event.preventDefault();

                let $popup = $('#thim-popup-login');
                $('body').addClass('thim-popup-active');
                $popup.addClass('active');
                if ($(this).hasClass('login')) {
                    $popup.addClass('sign-in');
                } else {
                    $popup.addClass('sign-up');
                }
            });

            $('#thim-popup-login .link-bottom a').on('click', function(e) {
                e.preventDefault();

                if ($(this).hasClass('login')) {
                    $('#thim-popup-login').removeClass('sign-up').addClass('sign-in');
                } else {
                    $('#thim-popup-login').removeClass('sign-in').addClass('sign-up');
                }
            });

            // Show login popup when click to LP buttons
            $('body:not(".logged-in") .enroll-course .button-enroll-course, body:not(".logged-in") form.purchase-course:not(".guest_checkout") .button:not(.button-add-to-cart)').
                on('click', function(e) {
                    e.preventDefault();

                    // if ($(window).width() > 1024) {
                    if ($('body').hasClass('thim-popup-feature')) {
                        $('.thim-link-login.thim-login-popup .login').trigger('click');
                    } else {
                        window.location.href = $(this).parent().find('input[name=redirect_to]').val();
                    }
                    // } else {
                    //     window.location.href = $(this).parent().find('input[name=redirect_to]').val();
                    // }
                });

            $(document).on('click', '#thim-popup-login', function(e) {
                if ($(e.target).attr('id') === 'thim-popup-login') {
                    $('body').removeClass('thim-popup-active');
                    $('#thim-popup-login').removeClass();
                }
            });
        },

        register_ajax: function() {
            $('#thim-popup-login form[name=registerformpopup]').on('submit', function(e) {
                e.preventDefault();

                if (!thim_eduma.validate_form($(this))) {
                    return false;
                }

                var $form = $(this),
                    data = {
                        action           : 'thim_register_ajax',
                        data             : $form.serialize() + '&wp-submit=' +
                            $form.find('input[type=submit]').val(),
                        register_security: $form.find('#register_security').
                            val(),
                    },
                    redirect_url = $form.find('input[name=redirect_to]').val(),
                    $elem = $('#thim-popup-login .thim-login-container');

                $elem.addClass('loading');
                $elem.find('.message').slideDown().remove();

                $.ajax({
                    type   : 'POST',
                    url    : ajaxurl,
                    data   : data,
                    success: function(response) {
                        $elem.removeClass('loading');
                        if (typeof response.data !== 'undefined') {
                            $elem.find('.popup-message').html(response.data.message);
                        }
                        if (response.success === true) {
                            if ($form.hasClass('auto_login')) {
                                window.location.href = redirect_url;
                            }
                        } else {
                            var $captchaIframe = $('#thim-popup-login .gglcptch iframe');
                            if ($captchaIframe.length > 0) {
                                $captchaIframe.attr('src', $captchaIframe.attr('src')); // reload iframe
                            }
                        }
                    },
                });
            });
        },

        login_ajax: function() {
            $('#thim-popup-login form[name=loginpopopform]').submit(function(event) {
                event.preventDefault();

                if (!thim_eduma.validate_form($(this))) {
                    return false;
                }

                var form = $(this),
                    $elem = $('#thim-popup-login .thim-login-container'),
                    wp_submit = $elem.find('input[type=submit]').val();

                $elem.addClass('loading');
                $elem.find('.message').slideDown().remove();

                var data = {
                    action: 'thim_login_ajax',
                    data  : form.serialize() + '&wp-submit=' + wp_submit,
                };

                $.post(ajaxurl, data, function(response) {
                    try {
                        response = JSON.parse(response);
                        $elem.find('.thim-login').append(response.message);
                        if (response.code == '1') {
                            if (response.redirect) {
                                if (window.location.href == response.redirect) {
                                    location.reload();
                                } else {
                                    window.location.href = response.redirect;
                                }
                            } else {
                                location.reload();
                            }
                        } else {
                            var $captchaIframe = $('#thim-popup-login .gglcptch iframe');
                            if ($captchaIframe.length > 0) {
                                $captchaIframe.attr('src', $captchaIframe.attr('src')); // reload iframe
                            }
                        }
                    } catch (e) {
                        return false;
                    }
                    $elem.removeClass('loading');
                });

                return false;
            });
        },

        form_submission_validate: function() {
            // Form login
            $('.form-submission-login form[name=loginform]').on('submit', function(e) {
                if (!thim_eduma.validate_form($(this))) {
                    e.preventDefault();
                    return false;
                }
            });

            // Form register
            $('.form-submission-register form[name=registerform]').on('submit', function(e) {
                if (!thim_eduma.validate_form($(this))) {
                    e.preventDefault();
                    return false;
                }
            });

            // Form lost password
            $('.form-submission-lost-password form[name=lostpasswordform]').on('submit', function(e) {
                if (!thim_eduma.validate_form($(this))) {
                    e.preventDefault();
                    return false;
                }
            });
        },

        thim_TopHeader: function() {
            var header = $('#masthead'),
                height_sticky_header = header.outerHeight(true),
                content_pusher = $('#wrapper-container .content-pusher'),
                top_site_main = $('#wrapper-container .top_site_main');

            if (header.hasClass('header_overlay')) { // Header overlay
                top_site_main.css({'padding-top': height_sticky_header + 'px'});
                $(window).resize(function() {
                    let height_sticky_header = header.outerHeight(true);
                    top_site_main.css({'padding-top': height_sticky_header + 'px'});
                });
            } else { // Header default
                content_pusher.css({'padding-top': height_sticky_header + 'px'});
                $(window).resize(function() {
                    let height_sticky_header = header.outerHeight(true);
                    content_pusher.css({'padding-top': height_sticky_header + 'px'});
                });

            }
        },

        ctf7_input_effect: function() {
            let $ctf7_edtech = $('.form_developer_course'),
                $item_input = $ctf7_edtech.find('.field_item input'),
                $submit_wrapper = $ctf7_edtech.find('.submit_row');

            $item_input.focus(function() {
                $(this).parent().addClass('focusing');
            }).blur(function() {
                $(this).parent().removeClass('focusing');
            });

            $submit_wrapper.on('click', function() {
                $(this).closest('form').submit();
            });
        },

        thim_course_filter: function() {
            let $body = $('body');

            if (!$body.hasClass('learnpress') || !$body.hasClass('archive')) {
                return;
            }

            let ajaxCall = function(data) {
                return $.ajax({
                    url       : $('#lp-archive-courses').data('allCoursesUrl'), //using for course category page
                    type      : 'POST',
                    data      : data,
                    dataType  : 'html',
                    beforeSend: function() {
                        $('#thim-course-archive').addClass('loading');
                    },
                }).fail(function() {
                    $('#thim-course-archive').removeClass('loading');
                }).done(function(data) {
                    /*if (typeof history.pushState === 'function') {
                        history.pushState(orderby, null, url);
                    }*/
                    let $document = $($.parseHTML(data));

                    $('#thim-course-archive').replaceWith($document.find('#thim-course-archive'));
                    $('.learn-press-pagination ul.page-numbers').
                        replaceWith($document.find('.learn-press-pagination ul.page-numbers'));
                    $('.thim-course-top .course-index span').
                        replaceWith($document.find('.thim-course-top .course-index span'));
                });
            };

            let sendData = {
                s             : '',
                ref           : 'course',
                post_type     : 'lp_course',
                course_orderby: 'newly-published',
                course_paged  : 1,
            };

            /*
            * Handle courses sort ajax
            * */
            $(document).on('change', '.thim-course-order > select', function() {
                sendData.s = $('.courses-searching .course-search-filter').val();
                sendData.course_orderby = $(this).val();
                sendData.course_paged = 1;

                ajaxCall(sendData);
            });

            /*
            * Handle pagination ajax
            * */

            $(document).on('click', '#lp-archive-courses > .learn-press-pagination a.page-numbers', function(e) {
                e.preventDefault();

                $('html, body').animate({
                    'scrollTop': $('.site-content').offset().top - 140,
                }, 1000);

                let pageNum = parseInt($(this).text()),
                    paged = pageNum ? pageNum : 1,
                    cateArr = [], instructorArr = [],
                    cpage = $('.learn-press-pagination.navigation.pagination ul.page-numbers li span.page-numbers.current').text(),
                    isNext = $(this).hasClass('next') && $(this).hasClass('page-numbers'),
                    isPrev = $(this).hasClass('prev') && $(this).hasClass('page-numbers');
                if(!pageNum){
                    if(isNext){
                            paged = parseInt(cpage)+1;
                    }
                    if(isPrev){
                            paged = parseInt(cpage)-1;
                    }
                }

                $('form.thim-course-filter').find('input.filtered').each(function() {
                    switch ($(this).attr('name')) {
                        case 'course-cate-filter':
                            cateArr.push($(this).val());
                            break;
                        case 'course-instructor-filter':
                            instructorArr.push($(this).val());
                            break;
                        case 'course-price-filter':
                            sendData.course_price_filter = $(this).val();
                            break;
                        default:
                            break;
                    }
                });

                if ($body.hasClass('category') && $('.list-cate-filter').length <= 0) {
                    let bodyClass = $body.attr('class'),
                        cateClass = bodyClass.match(/category\-\d+/gi)[0],
                        cateID = cateClass.split('-').pop();

                    cateArr.push(cateID);
                }

                sendData.course_cate_filter = cateArr;
                sendData.course_instructor_filter = instructorArr;

                sendData.s = $('.courses-searching .course-search-filter').val();
                sendData.course_orderby = $('.thim-course-order > select').val();
                sendData.course_paged = paged;

                ajaxCall(sendData);
            });

            /*
            * Handle filter form click ajax
            * */
            $('form.thim-course-filter').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serializeArray(),
                    cateArr = [], instructorArr = [];

                if (!formData.length) {
                    return;
                }

                $('html, body').animate({
                    'scrollTop': $('.site-content').offset().top - 140,
                }, 1000);

                $(this).find('input').each(function() {
                    let form_input = $(this);
                    form_input.removeClass('filtered');

                    if (form_input.is(':checked')) {
                        form_input.addClass('filtered');
                    }
                });

                $.each(formData, function(index, filter) {
                    switch (filter.name) {
                        case 'course-cate-filter':
                            cateArr.push(filter.value);
                            break;
                        case 'course-instructor-filter':
                            instructorArr.push(filter.value);
                            break;
                        case 'course-price-filter':
                            sendData.course_price_filter = filter.value;
                            break;
                        default:
                            break;
                    }
                });

                if ($body.hasClass('category') && $('.list-cate-filter').length <= 0) {
                    let bodyClass = $body.attr('class'),
                        cateClass = bodyClass.match(/category\-\d+/gi)[0],
                        cateID = cateClass.split('-').pop();

                    cateArr.push(cateID);
                }

                sendData.course_cate_filter = cateArr;
                sendData.course_instructor_filter = instructorArr;
                sendData.course_paged = 1;

                ajaxCall(sendData);
            });
        },

        mobile_menu_toggle: function() {
            $(document).on('click', '.menu-mobile-effect', function(e) {
                e.stopPropagation();
                $('body').toggleClass('mobile-menu-open');
            });

            $(document).on('click', '.mobile-menu-wrapper', function(e) {
                $('body').removeClass('mobile-menu-open');
            });

            $(document).on('click', '.mobile-menu-inner', function(e) {
                e.stopPropagation();
            });
        },

        thim_menu: function() {

            //Add class for masthead
            var $header = $('#masthead.sticky-header'),
                off_Top = ($('.content-pusher').length > 0) ? $('.content-pusher').
                    offset().top : 0,
                menuH = $header.outerHeight(),
                latestScroll = 0;
            if ($(window).scrollTop() > 2) {
                $header.removeClass('affix-top').addClass('affix');
            }
            $(window).scroll(function() {
                var current = $(this).scrollTop();
                if (current > 2) {
                    $header.removeClass('affix-top').addClass('affix');
                } else {
                    $header.removeClass('affix').addClass('affix-top');
                }

                if (current > latestScroll && current > menuH + off_Top) {
                    if (!$header.hasClass('menu-hidden')) {
                        $header.addClass('menu-hidden');
                    }
                } else {
                    if ($header.hasClass('menu-hidden')) {
                        $header.removeClass('menu-hidden');
                    }
                }

                latestScroll = current;
            });

            //Submenu position
            $('.wrapper-container:not(.mobile-menu-open) .site-header .navbar-nav > .menu-item').each(function() {
                if ($('>.sub-menu', this).length <= 0) {
                    return;
                }

                let elm = $('>.sub-menu', this),
                    off = elm.offset(),
                    left = off.left,
                    width = elm.width();

                let navW = $('.thim-nav-wrapper').width(),
                    isEntirelyVisible = (left + width <= navW);

                if (!isEntirelyVisible) {
                    elm.addClass('dropdown-menu-right');
                } else {
                    let subMenu2 = elm.find('>.menu-item>.sub-menu');

                    if (subMenu2.length <= 0) {
                        return;
                    }

                    let off = subMenu2.offset(),
                        left = off.left,
                        width = subMenu2.width();

                    let isEntirelyVisible = (left + width <= navW);

                    if (!isEntirelyVisible) {
                        elm.addClass('dropdown-left-side');
                    }
                }
            });

            //Show submenu when hover
            // var $menuItem = $(
            //     '.wrapper-container:not(.mobile-menu-open) .site-header .navbar-nav >li,' +
            //     '.wrapper-container:not(.mobile-menu-open) .site-header .navbar-nav li,' +
            //     '.site-header .navbar-nav li ul li');
            //
            // $menuItem.on({
            //     'mouseenter': function() {
            //         $(this).children('.sub-menu').stop(true, false).fadeIn(250);
            //     },
            //     'mouseleave': function() {
            //         $(this).
            //             children('.sub-menu').
            //             stop(true, false).
            //             fadeOut(250);
            //     },
            // });

            let $headerLayout = $('header#masthead');
            let magicLine = function() {
                if ($(window).width() > 768) {
                    //Magic Line
                    var menu_active = $(
                        '#masthead .navbar-nav>li.menu-item.current-menu-item,#masthead .navbar-nav>li.menu-item.current-menu-parent, #masthead .navbar-nav>li.menu-item.current-menu-ancestor');
                    if (menu_active.length > 0) {
                        menu_active.before('<span id="magic-line"></span>');
                        var menu_active_child = menu_active.find(
                            '>a,>span.disable_link,>span.tc-menu-inner'),
                            menu_left = menu_active.position().left,
                            menu_child_left = parseInt(menu_active_child.css('padding-left')),
                            magic = $('#magic-line');

                        magic.width(menu_active_child.width()).
                            css('left', Math.round(menu_child_left + menu_left)).
                            data('magic-width', magic.width()).
                            data('magic-left', magic.position().left);

                    } else {
                        var first_menu = $(
                            '#masthead .navbar-nav>li.menu-item:first-child');
                        first_menu.before('<span id="magic-line"></span>');
                        var magic = $('#magic-line');
                        magic.data('magic-width', 0);
                    }

                    var nav_H = parseInt($('.site-header .navigation').outerHeight());
                    magic.css('bottom', nav_H - (nav_H - 90) / 2 - 64);

                    $('#masthead .navbar-nav>li.menu-item').on({
                        'mouseenter': function() {
                            var elem = $(this).
                                    find('>a,>span.disable_link,>span.tc-menu-inner'),
                                new_width = elem.width(),
                                parent_left = elem.parent().position().left,
                                left = parseInt(elem.css('padding-left'));
                            if (!magic.data('magic-left')) {
                                magic.css('left', Math.round(parent_left + left));
                                magic.data('magic-left', 'auto');
                            }
                            magic.stop().animate({
                                left : Math.round(parent_left + left),
                                width: new_width,
                            });
                        },
                        'mouseleave': function() {
                            magic.stop().animate({
                                left : magic.data('magic-left'),
                                width: magic.data('magic-width'),
                            });
                        },
                    });
                }
            };

            if (!$headerLayout.hasClass('header_v4')) {
                magicLine();
            }

            var subMenuPosition = function(menuItem) {
                var $menuItem = menuItem,
                    $container = $menuItem.closest('.container, .header_full'),
                    $subMenu = $menuItem.find('>.sub-menu'),
                    $menuItemWidth = $menuItem.width(),
                    $containerWidth = $container.width(),
                    $subMenuWidth = $subMenu.width(),
                    $subMenuDistance = $subMenuWidth / 2,
                    paddingContainer = 15;

            };
        },

        thim_carousel: function($scope) {
            if (jQuery().owlCarousel) {
                $('.thim-gallery-images').owlCarousel({
                    autoPlay   : false,
                    singleItem : true,
                    stopOnHover: true,
                    pagination : true,
                    autoHeight : false,
                });

                $('.thim-carousel-wrapper').each(function() {
                    var item_visible = $(this).data('visible') ? parseInt(
                        $(this).data('visible')) : 4,
                        item_desktopsmall = $(this).data('desktopsmall') ? parseInt(
                            $(this).data('desktopsmall')) : item_visible,
                        itemsTablet = $(this).data('itemtablet') ? parseInt(
                            $(this).data('itemtablet')) : 2,
                        itemsMobile = $(this).data('itemmobile') ? parseInt(
                            $(this).data('itemmobile')) : 1,
                        pagination = !!$(this).data('pagination'),
                        navigation = !!$(this).data('navigation'),
                        autoplay = $(this).data('autoplay') ? parseInt(
                            $(this).data('autoplay')) : false,
                        navigation_text = ($(this).data('navigation-text') &&
                            $(this).data('navigation-text') === '2') ? [
                            '<i class=\'fa fa-long-arrow-left \'></i>',
                            '<i class=\'fa fa-long-arrow-right \'></i>',
                        ] : [
                            '<i class=\'fa fa-chevron-left \'></i>',
                            '<i class=\'fa fa-chevron-right \'></i>',
                        ];

                    $(this).owlCarousel({
                        items            : item_visible,
                        itemsDesktop     : [1200, item_visible],
                        itemsDesktopSmall: [1024, item_desktopsmall],
                        itemsTablet      : [768, itemsTablet],
                        itemsMobile      : [480, itemsMobile],
                        navigation       : navigation,
                        pagination       : pagination,
                        lazyLoad         : true,
                        autoPlay         : autoplay,
                        navigationText   : navigation_text,
                    });
                });

                $('.thim-carousel-course-categories .thim-course-slider, .thim-carousel-course-categories-tabs .thim-course-slider').
                    each(function() {
                        var item_visible = $(this).data('visible') ? parseInt($(this).data('visible')) : 7,
                            item_desktop = $(this).data('desktop') ? parseInt($(this).data('desktop')) : 7,
                            item_desktopsmall = $(this).data('desktopsmall')
                                ? parseInt($(this).data('desktopsmall'))
                                : 6,
                            item_tablet = $(this).data('tablet') ? parseInt($(this).data('tablet')) : 4,
                            item_mobile = $(this).data('mobile') ? parseInt($(this).data('mobile')) : 2,
                            pagination = !!$(this).data('pagination'),
                            navigation = !!$(this).data('navigation'),
                            autoplay = $(this).data('autoplay') ? parseInt($(this).data('autoplay')) : false,
                            is_rtl = $('body').hasClass('rtl');

                        $(this).owlCarousel({
                            items            : item_visible,
                            itemsDesktop     : [1800, item_desktop],
                            itemsDesktopSmall: [1024, item_desktopsmall],
                            itemsTablet      : [768, item_tablet],
                            itemsMobile      : [480, item_mobile],
                            navigation       : navigation,
                            pagination       : pagination,
                            autoPlay         : autoplay,
                            navigationText   : [
                                '<i class=\'fa fa-chevron-left \'></i>',
                                '<i class=\'fa fa-chevron-right \'></i>',
                            ],
                        });
                    });
            }
        },

        thim_contentslider: function($scope) {
            $('.thim-testimonial-slider').each(function() {
                var elem = $(this),
                    item_visible = parseInt(elem.data('visible')),
                    item_time = parseInt(elem.data('time')),
                    autoplay = elem.data('auto') ? true : false,
                    item_ratio = elem.data('ratio') ? elem.data('ratio') : 1.18,
                    item_padding = elem.data('padding') ? elem.data('padding') : 15,
                    item_activepadding = elem.data('activepadding') ? elem.data(
                        'activepadding') : 0,
                    item_width = elem.data('width') ? elem.data('width') : 100,
                    mousewheel = !!elem.data('mousewheel');

                var testimonial_slider = $(this).thimContentSlider({
                    items            : elem,
                    itemsVisible     : item_visible,
                    mouseWheel       : mousewheel,
                    autoPlay         : autoplay,
                    pauseTime        : item_time,
                    itemMaxWidth     : item_width,
                    itemMinWidth     : item_width,
                    activeItemRatio  : item_ratio,
                    activeItemPadding: item_activepadding,
                    itemPadding      : item_padding,
                });

            });
        },

        counter_box: function() {
            if (jQuery().waypoint) {
                jQuery('.counter-box').waypoint(function() {
                    jQuery(this).find('.display-percentage').each(function() {
                        var percentage = jQuery(this).data('percentage');
                        jQuery(this).
                            countTo({
                                from           : 0,
                                to             : percentage,
                                refreshInterval: 40,
                                speed          : 2000,
                            });
                    });
                }, {
                    triggerOnce: true,
                    offset     : '80%',
                });
            }
        }

    };

    $(document).ready(function() {
        thim_eduma.ready();
    });

    $(window).load(function() {
        thim_eduma.load();
    });

    $(window).resize(function() {
        thim_eduma.resize();
    });

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/thim-carousel-post.default',
            thim_eduma.thim_carousel);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-courses.default',
            thim_eduma.thim_carousel);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-course-categories.default',
            thim_eduma.thim_carousel);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-our-team.default',
            thim_eduma.thim_carousel);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-gallery-images.default',
            thim_eduma.thim_carousel);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-list-instructors.default',
            thim_eduma.thim_carousel);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-testimonials.default',
            thim_eduma.thim_contentslider);

        elementorFrontend.hooks.addAction('frontend/element_ready/thim-counters-box.default',
            thim_eduma.counter_box);
    });
})(jQuery);