/*================================================================*\

 Name: Scripts.js
 Author: ThemeHippo
 Author URI: https://themehippo.com/
 Version: 0.1

 \*================================================================*/

jQuery(function ($) {

    'use strict';

    /*================================================================*\
     ================================================================
     Table Of Contents
     ================================================================

     # Blank JS IIFE Wrapper
     # Browser to detect IE
     # Off Canvas Menu
     # Mega Menu widget nav menu sub
     # Enable Bootstrap tooltip
     # Drop Down menu
     # Nav Bar List Trigger
     # Twitter Feed on Footer Widget
     # Flickr Photo Feed
     # Magnific Image Popup
     # Social Share Button Popup
     # Google Map
     # MiniCart count on Added to cart
     # Login modal
     # Product Gallery
     # Catalogue Carousel
     # Category list toggle button
     # Search Form
     # WP Masonry Grid View
     # Material button effect
     # Material input style
     # WooCommerce Variation Change

     \*================================================================*/

    // ================================================================
    // Blank JS Wrapper
    // ================================================================

    (function () {

    }());

    // ================================================================
    // Check Is Mobile
    // ================================================================

    var isMobile = function () {
        var check = false;
        (function (a) {
            if (/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true
        })(navigator.userAgent || navigator.vendor || window.opera);
        return check;
    };

    // ================================================================
    // Off Canvas Menu
    // ================================================================

    (function () {

        // click button
        $('.navbar-toggle').HippoOffCanvasMenu({

            documentWrapper : '#wrapper',
            contentWrapper  : '.contents',
            position        : hippoJSObject.offcanvas_menu_position,    // class name
            // opener         : 'st-menu-open',         // class name
            effect          : hippoJSObject.offcanvas_menu_effect,  // class name
            closeButton     : '.close-sidebar',
            menuWrapper     : '#offcanvasmenu',                // class name
            documentPusher  : '.pusher'

        });

    }());

    // ================================================================
    // Mega Menu widget nav menu sub
    // ================================================================

    (function () {

        $('.megamenu-widget.widget_nav_menu .current-menu-item').parents("ul.sub-menu").addClass('show-child');

        $('.megamenu-widget.widget_nav_menu .menu-item-has-children > a').each(function () {
            $(this).append($('<i class="fa fa-angle-right child-opener"></i>'));
        });

        $('.megamenu-widget.widget_nav_menu .menu-item-has-children > ul').each(function () {
            $(this).prepend($('<li class="child-closer-wrapper"><a class="child-closer" href="#"><i class="fa fa-angle-left"></i> Back </a></li>'));
        });

        $('.megamenu-widget.widget_nav_menu .menu-item-has-children .child-opener').on('click', function (e) {

            e.preventDefault();

            $(this).closest('li').find('>ul').addClass('show-child');
            //var height = $(this).closest('li').find('>ul').height();
            var height = $(this).closest('li').find('>ul')[0].scrollHeight;
            $(this).closest('.megamenu-widget.widget_nav_menu > div > ul').css('height', height);

        });

        $('.megamenu-widget.widget_nav_menu .menu-item-has-children .child-closer').on('click', function (e) {

            e.preventDefault();

            $(this).closest('ul').removeClass('show-child');
            //var height = $(this).closest('ul').prev().closest('ul').height();
            var height = $(this).closest('ul').prev().closest('ul')[0].scrollHeight;

            if ($(this).closest('ul').prev().closest('ul').is($(this).closest('ul.menu'))) {
                $(this).closest('.megamenu-widget.widget_nav_menu > div > ul').css('height', '');
            }
            else {
                $(this).closest('.megamenu-widget.widget_nav_menu > div > ul').css('height', height);
            }
        });
    }());

    // ================================================================
    // Enable Bootstrap tooltip
    // ================================================================

    (function () {
        $('[data-toggle="tooltip"], [data-tooltip="tooltip"]').tooltip();
    }());

    // ================================================================
    // Drop Down menu
    // ================================================================

    (function () {

        function getIEVersion() {
            var match = navigator.userAgent.match(/(?:MSIE |Trident\/.*; rv:)(\d+)/);
            return match ? parseInt(match[1]) : false;
        }

        if (getIEVersion()) {
            $('html').addClass('ie ie' + getIEVersion());
        }

        if ($('html').hasClass('ie9') || $('html').hasClass('ie10')) {
            $('.submenu-wrapper').each(function () {
                $(this).addClass('no-pointer-events');
            });
        }

        var timer;

        $('li.dropdown').on('mouseenter', function (event) {

            event.stopImmediatePropagation();
            event.stopPropagation();

            $(this).removeClass('open menu-animating').addClass('menu-animating');
            var that = this;

            if (timer) {
                clearTimeout(timer);
                timer = null;
            }

            timer = setTimeout(function () {

                $(that).removeClass('menu-animating');
                $(that).addClass('open');

            }, 300);   // 300ms as css animation end time

        });

        // on mouse leave

        $('li.dropdown').on('mouseleave', function (event) {

            var that = this;

            $(this).removeClass('open menu-animating').addClass('menu-animating');

            if (timer) {
                clearTimeout(timer);
                timer = null;
            }

            timer = setTimeout(function () {

                $(that).removeClass('menu-animating');
                $(that).removeClass('open');

            }, 300);  // 300ms as animation end time

        });

    }());

    // ================================================================
    // Vertical navbar Trigger
    // ================================================================

    (function () {
        $('.menu-trigger').on('click', function () {
            $(this).toggleClass('trigger-close');
            $(this).next().toggleClass('show-nav-list');
        });

        $(document.body).on('click', function (e) {
            var container = $('.navbar-vertical-wrapper');

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.find('.menu-trigger').removeClass('trigger-close');
                container.find('.navbar-vertical').removeClass('show-nav-list');
            }
        });
    }());

    // ================================================================
    // Back to Top
    // ================================================================

    (function () {

        if (hippoJSObject.back_to_top) {

            $('body').append('<div class="hidden-xs" id="toTop"><i class="zmdi zmdi-chevron-up"></i></div>');

            $(window).scroll(function () {
                if ($(this).scrollTop() != 0) {
                    $('#toTop').fadeIn();
                }
                else {
                    $('#toTop').fadeOut();
                }
            });

            $('#toTop').on('click', function () {
                $("html, body").animate({scrollTop : 0}, 600);
                return false;
            });
        }
    }());

    // ================================================================
    // Twitter Feed on Footer Widget
    // ================================================================

    /**
     * ### HOW TO CREATE A VALID ID TO USE: ###
     * Go to www.twitter.com and sign in as normal, go to your settings page.
     * Go to "Widgets" on the left hand side.
     * Create a new widget for what you need eg "user time line" or "search" etc.
     * Feel free to check "exclude replies" if you don't want replies in results.
     * Now go back to settings page, and then go back to widgets page and
     * you should see the widget you just created. Click edit.
     * Look at the URL in your web browser, you will see a long number like this:
     * 567185781790228482
     * Use this as your ID below instead!
     */
    if (typeof twitterFetcher === "function") {
        (function () {

            var widgetId       = $('.twitterWidget').attr('data-widget-id');
            var maxTweetNumber = $('.twitterWidget').attr('data-max-tweet');
            var twitterConfig  = {
                id              : widgetId, //put your widget ID here
                domId           : "twitterWidget",
                maxTweets       : maxTweetNumber,
                enableLinks     : true,
                showUser        : false,
                showTime        : true,
                showInteraction : false,
                customCallback  : handleTweets
            };
            twitterFetcher.fetch(twitterConfig);

            function handleTweets(tweets) {
                var x    = tweets.length;
                var n    = 0;
                var html = "";
                while (n < x) {
                    html += '<div class="item">' + tweets[n] +
                        "</div>";
                    n++
                }
                $(".twitter-widget").html(html);
                $(".twitter_retweet_icon").html(
                    '<i class="fa fa-retweet"></i>');
                $(".twitter_reply_icon").html(
                    '<i class="fa fa-reply"></i>');
                $(".twitter_fav_icon").html(
                    '<i class="fa fa-star"></i>');
                $(".twitter-widget").owlCarousel({
                    dots     : false,
                    items    : 1,
                    loop     : true,
                    autoplay : true
                });
            }
        }());
    }

    // ================================================================
    // Single Product Thumb Carousel
    // ================================================================

    (function () {

        if ($().owlCarousel) {
            $(document).ready(function () {

                var owl = $('.flex-control-thumbs');

                owl.owlCarousel({
                    items   : 5,
                    dots    : false,
                    nav     : false,
                    navText : ['', ''],
                    margin  : 2
                    // autoWidth : true
                    // itemElement : 'li'
                });

                $('.hippo-product-gallery-navigation .previous').on('click', function (event) {
                    event.preventDefault();
                    $('.woocommerce-product-gallery').flexslider('prev');
                    owl.trigger('prev.owl.carousel');
                });

                $('.hippo-product-gallery-navigation .next').on('click', function (event) {
                    event.preventDefault();
                    $('.woocommerce-product-gallery').flexslider('next');
                    owl.trigger('next.owl.carousel');
                });

            });
        }

    })();

    // ================================================================
    // Flickr Photo Feed
    // ================================================================
    if ($().jflickrfeed) {
        (function () {

            $('.flickr-photo-stream').jflickrfeed({
                limit        : $('.flickr-photo-stream').attr('data-photo-limit'),
                qstrings     : {
                    id : $('.flickr-photo-stream').attr('data-flickr-id')
                },
                itemTemplate : '<li>' +
                '<a href="{{image}}" title="{{title}}">' +
                '<img src="{{image_s}}" alt="{{title}}" />' +
                '</a>' +
                '</li>'
            });
        }());
    }

    // ================================================================
    // Magnific Image Popup
    // ================================================================

    (function () {
        $(window).load(function () {
            if ($().magnificPopup) {
                $(".flickr-photo-stream a[href$='.png'], .flickr-photo-stream a[href$='.jpg'], .flickr-photo-stream a[href$='.gif'], .element-lightbox").magnificPopup({

                    gallery      : {
                        enabled : true
                    },
                    removalDelay : 300, // Delay in milliseconds before popup is removed
                    type         : 'image'
                });
            }
        });
    }());

    // ================================================================
    // Social Share Button Popup
    // ================================================================

    (function () {
        $('.social a').on('click', function () {
            var newwindow = window.open($(this).attr('href'), '', 'height=450,width=700');
            if (window.focus) {
                newwindow.focus()
            }
            return false;
        });
    }());

    // ================================================================
    // MiniCart count on Added to cart
    // ================================================================

    (function () {
        $(document.body).on('added_to_cart', function (e, data) {
            $.get(hippoJSObject.ajax_url, {
                action : 'hippo_cart_count'
            }, function (data, status) {
                $('#mini-cart-total').text(data);
            });
        });
    })();

    // ================================================================
    // MiniCart remove item ajax
    // ================================================================

    (function () {
        $(document.body).on('click', '.hippo-remove-from-mini-cart-ajax', function (e) {

            e.preventDefault();

            var item_key   = $.trim($(this).attr('data-item_key'));
            var product_id = $.trim($(this).attr('data-product_id'));
            var that       = this;

            $(document.body).trigger('hippo_before_remove_from_mini_cart', [that, product_id, item_key]);

            $.get(hippoJSObject.ajax_url, {
                action      : 'hippo_remove_from_mini_cart',
                remove_item : item_key
            }, function (data, status) {
                $(document.body).trigger('added_to_cart');
                $(document.body).trigger('hippo_wc_fragments_refresh');
                $(document.body).trigger('hippo_after_removed_from_mini_cart', [that, product_id]);
            });
        });
    })();

    // ================================================================
    // Before Remove From MiniCart Action
    // ================================================================

    (function () {
        $(document.body).on('hippo_before_remove_from_mini_cart', function (e, that, product_id) {

            $(that).closest('.mini_cart_item').addClass('removing');

            if ($().block) {
                $(that).closest('.mini_cart_item').block({message : null});
            }
        });

    })();

    // ================================================================
    // After Removed From MiniCart Action
    // ================================================================

    (function () {
        $(document.body).on('hippo_after_removed_from_mini_cart', function (e, that, product_id) {

            //$(that).closest('.mini_cart_item').removeClass('removing');

            if ($().unblock) {
                $(that).closest('.mini_cart_item').unblock();
            }
        });

    })();

    // ================================================================
    // MiniCart refresh
    // ================================================================

    (function () {

        $(document.body).on('hippo_wc_fragments_refresh', function () {

            /* Storage Handling */
            var $supports_html5_storage;
            try {
                $supports_html5_storage = ( 'sessionStorage' in window && window.sessionStorage !== null );

                window.sessionStorage.setItem('wc', 'test');
                window.sessionStorage.removeItem('wc');
            }
            catch (err) {
                $supports_html5_storage = false;
            }

            var $fragment_refresh = {
                url     : wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                type    : 'POST',
                success : function (data) {
                    if (data && data.fragments) {

                        $.each(data.fragments, function (key, value) {
                            $(key).replaceWith(value);
                        });

                        if ($supports_html5_storage) {
                            sessionStorage.setItem(wc_cart_fragments_params.fragment_name, JSON.stringify(data.fragments));
                            sessionStorage.setItem('wc_cart_hash', data.cart_hash);
                        }

                        $(document.body).trigger('wc_fragments_refreshed');
                    }
                }
            };

            $.ajax($fragment_refresh);
        });
    })();

    // ================================================================
    // Currency Switcher Cart Fragment Refresh
    // ================================================================
    if (hippoJSObject.currency_switcher) {
        (function () {
            $(document.body).trigger('hippo_wc_fragments_refresh');
        }());
    }

    // ================================================================
    // Added to wishlist
    // ================================================================

    (function () {
        $(document.body).on('init-wishlist added-to-wishlist', function (e, el, data) {

            $.get(hippoJSObject.ajax_url, {
                action : 'hippo_wishlist_total_count'
            }, function (data, status) {
                //console.log(data);
                $('#hippo-wishlist-total').text(data);
            });

        });
    }());

    // ================================================================
    // Login modal
    // ================================================================

    (function () {
        $('.toggle').on('click', function () {
            $('.form-container').stop().addClass('active');
        });

        $('.close').on('click', function () {
            $('.form-container').stop().removeClass('active');
        });
    }());

    // ================================================================
    // Product Gallery
    // ================================================================

    if ($().flexslider) {
        $(window).on('load', function () {
            // The slider being synced must be initialized first
            /*$('.hippo-thumb').flexslider({
             animation     : "slide",
             controlNav    : false,
             animationLoop : true,
             slideshow     : false,
             itemWidth     : 125,
             asNavFor      : '.hippo-gallery'
             });

             $('.hippo-gallery').flexslider({
             animation     : "slide",
             directionNav  : false,
             controlNav    : false,
             animationLoop : false,
             slideshow     : false,
             sync          : ".hippo-thumb"
             });
             */
            // Navigation
            /*$('.hippo-product-gallery-navigation .previous').on('click', function (event) {
             event.preventDefault();
             $('.woocommerce-product-gallery').flexslider('prev');
             });

             $('.hippo-product-gallery-navigation .next').on('click', function (event) {
             event.preventDefault();
             $('.woocommerce-product-gallery').flexslider('next')
             });*/
        }());
    }

    // ================================================================
    // Category list toggle button
    // ================================================================

    (function () {
        $('.hippo-button-toggle').on('click', function () {
            $(this).toggleClass('open');
            $('ul').toggleClass('show-list');
        });
    }());

    // ================================================================
    // Product filter
    // ================================================================

    (function () {
        $('.shop-filter-trigger').on('click', function () {
            $('.shop-sidebar').slideToggle('slow');
        });
    }());

    // ================================================================
    // Search Form
    // ================================================================

    (function () {
        $('.control').on('click', function () {
            $('body').addClass('mode-search');
            $('.input-search').trigger('focus');
        });

        $('.icon-close').on('click', function () {
            $('body').removeClass('mode-search');
        });
    }());

    // ================================================================
    // WP Masonry Grid View
    // ================================================================
    if ($().masonry) {
        $(window).load(function () {
            var $masonry = $('.grid-post-content').masonry({
                "columnWidth" : ".masonry-grid"
            });
            _.delay(function () {
                $masonry.masonry();
            }, 1000);
        });
    }
    // ================================================================
    // Material button effects
    // ================================================================

    (function () {

        var event = isMobile() ? 'touchstart' : 'mousedown';

        $(document.body).on(event, '.waves-effect', function (e) {
            e.stopPropagation();
            var target, rect, rippleOld, size, rippleNew, top, left, time;

            target = $(this);
            rect   = target[0].getBoundingClientRect();

            // Clearing old ripple
            rippleOld = $(target).find('.ripple');
            $(rippleOld).remove();
            clearTimeout(time);
            size = Math.max(rect.width, rect.height);

            // Appending ripple
            rippleNew = $('<span/>', {
                class : 'ripple',
                css   : {
                    height : size + 'px',
                    width  : size + 'px'
                }
            }).appendTo($(target));
            top       = e.pageY - rect.top - rippleNew[0].offsetHeight / 2 - document.body.scrollTop;
            left      = e.pageX - rect.left - rippleNew[0].offsetWidth / 2 - document.body.scrollLeft;
            $(rippleNew).css('top', top + 'px');
            $(rippleNew).css('left', left + 'px');

            time = setTimeout(function () {
                $(rippleNew).remove();
            }, 750); // .ripple animation time wise
        });
    }());

    // ================================================================
    // Material input styles
    // ================================================================

    (function () {

        // Check default values
        $(".input-field .form-control").each(function () {
            if ($(this).val() !== "") {
                $(this).closest('.input-field').addClass("is-completed");
            }
        });

        // Active and focused on focus
        $(".input-field .form-control").on('focus', function () {
            $(this).closest('.input-field').addClass("is-active is-completed");
        });

        // Inactive on focusout
        $(".input-field .form-control").on('focusout', function () {
            if ($(this).val() === "") {
                $(this).closest('.input-field').removeClass("is-completed");
            }
            $(this).closest('.input-field').removeClass("is-active");
        });
    }());

    // ================================================================
    // WooCommerce Variation Change
    // ================================================================

    (function () {

        $('.variations_form ul.variable-items-wrapper').each(function (i, el) {

            var select = $(this).prev('select');
            var li     = $(this).find('li');

            $(this).on('click', 'li:not(.selected)', function () {
                var value = $(this).data('value');
                li.removeClass('selected');
                select.val(value).trigger('change');
                $(this).addClass('selected');
            });

            $(this).on('click', 'li.selected', function () {
                li.removeClass('selected');
                select.val('').trigger('change');
                select.trigger('click');
                select.trigger('focusin');
                select.trigger('touchstart');
            });

        });
    }());

    (function () {

        $('.variations_form').on('reset_data', function (event) {

            $(this).find('ul.variable-items-wrapper').each(function () {

                var li = $(this).find('li');

                li.each(function () {
                    $(this).removeClass('selected');
                    $(this).removeClass('disabled');
                });
            });
        });
        $('.variations_form').on('woocommerce_update_variation_values', function (event) {

            $(this).find('ul.variable-items-wrapper').each(function () {

                var selected = '';
                var options  = $(this).prev('select').find('option');
                var current  = $(this).prev('select').find('option:selected');
                var eq       = $(this).prev('select').find('option').eq(1);
                var li       = $(this).find('li');
                var selects  = [];

                options.each(function () {
                    if ($(this).val() != '') {
                        selects.push($(this).val());
                        selected = current ? current.val() : eq.val();
                    }
                });

                _.delay(function () {
                    li.each(function () {
                        var value = $(this).data('value');
                        $(this).removeClass('selected disabled');

                        if (_.contains(selects, value)) {
                            $(this).removeClass('disabled');

                            if (value == selected) {
                                $(this).addClass('selected');
                            }
                        }
                        else {
                            $(this).addClass('disabled');
                        }
                    });
                }, 1);

            });
        });

    }());

    // ================================================================
    // Material Style SelectBox
    // ================================================================

    (function () {

        $('select').not('.hippo-variation-select-box, .hide, #calc_shipping_country, #calc_shipping_state, #shipping_country, #shipping_state, #billing_country, #billing_state, #rating, [multiple], :disabled').each(function (i, el) {

            var rect    = $(this)[0].getBoundingClientRect();
            var options = $(this).find('option');
            var val     = $(this).val();
            var text    = $(this).find(':selected').text();

            var tpl = '<div class="hippo-material-select-wrapper" style="width: ' + (rect.width + 20) + 'px">';

            tpl += '<ul class="hippo-material-select-list">';

            options.each(function (i, opt) {

                if ($(this).prop('selected')) {
                    tpl += '<li class="hippo-material-selected" data-value="' + $(this).val() + '"> ' + $(this).text() + ' </li>';
                }
                else if ($(this).prop('disabled')) {
                    tpl += '<li class="hippo-material-disabled" data-value="' + $(this).val() + '"> ' + $(this).text() + ' </li>';
                }
                else {
                    tpl += '<li data-value="' + $(this).val() + '"> ' + $(this).text() + ' </li>';
                }

            });

            tpl += '</ul>';

            tpl += '<div class="hippo-material-select-contents">';
            tpl += '<div data-value="' + val + '" class="hippo-material-selected-item">' + text + '</div>';
            tpl += '<i class="fa fa-angle-down"></i></div>';

            $(this).addClass('hide').after(tpl);

        });

        $(document.body).on('click', '.hippo-material-select-contents', function () {
            $(this).parent().addClass('hippo-material-select-open');
        });

        $(document.body).on('click', '.hippo-material-select-list > li', function () {

            var current_value = $(this).attr('data-value');
            var current_text  = $(this).text();

            $(this).closest('.hippo-material-select-wrapper').prev('select').val(current_value);
            $(this).closest('.hippo-material-select-wrapper').removeClass('hippo-material-select-open');
            $(this).closest('.hippo-material-select-wrapper').find('li').removeClass('hippo-material-selected');
            $(this).closest('.hippo-material-select-wrapper').find('li[data-value="' + current_value + '"]').addClass('hippo-material-selected');
            $(this).closest('.hippo-material-select-wrapper').find('.hippo-material-selected-item').text(current_text).attr('data-value', current_value);
            $(this).closest('.hippo-material-select-wrapper').prev('select').trigger('change');

            // Woocommerce Country to state change
            $(document.body).trigger('country_to_state_changed');
        });

        $(document.body).on('click', function (e) {
            var container = $('.hippo-material-select-wrapper');

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.removeClass('hippo-material-select-open');
            }
        });

    }());

    // ================================================================
    // Enable Select2 on Country Select and State Select
    // ================================================================

    (function () {

        function getEnhancedSelectFormatString() {
            var formatString = {
                formatMatches         : function (matches) {
                    if (1 === matches) {
                        return wc_country_select_params.i18n_matches_1;
                    }

                    return wc_country_select_params.i18n_matches_n.replace('%qty%', matches);
                },
                formatNoMatches       : function () {
                    return wc_country_select_params.i18n_no_matches;
                },
                formatAjaxError       : function () {
                    return wc_country_select_params.i18n_ajax_error;
                },
                formatInputTooShort   : function (input, min) {
                    var number = min - input.length;

                    if (1 === number) {
                        return wc_country_select_params.i18n_input_too_short_1;
                    }

                    return wc_country_select_params.i18n_input_too_short_n.replace('%qty%', number);
                },
                formatInputTooLong    : function (input, max) {
                    var number = input.length - max;

                    if (1 === number) {
                        return wc_country_select_params.i18n_input_too_long_1;
                    }

                    return wc_country_select_params.i18n_input_too_long_n.replace('%qty%', number);
                },
                formatSelectionTooBig : function (limit) {
                    if (1 === limit) {
                        return wc_country_select_params.i18n_selection_too_long_1;
                    }

                    return wc_country_select_params.i18n_selection_too_long_n.replace('%qty%', limit);
                },
                formatLoadMore        : function () {
                    return wc_country_select_params.i18n_load_more;
                },
                formatSearching       : function () {
                    return wc_country_select_params.i18n_searching;
                }
            };

            return formatString;
        }

        // Select2 Enhancement if it exists

        if ($().select2) {

            var wc_country_select_select2 = function () {
                $('select.country_select:visible, select.country_to_state:visible, select.state_select:visible').each(function () {
                    var select2_args = $.extend({
                        placeholder       : $(this).attr('placeholder'),
                        placeholderOption : 'first',
                        width             : '100%'
                    }, getEnhancedSelectFormatString());

                    $(this).select2(select2_args);
                });
            };

            wc_country_select_select2();

            $(document.body).bind('country_to_state_changed', function () {
                wc_country_select_select2();
            });

            $(document).off("click", ".shipping-calculator-button");

            $(document).on('click', '.shipping-calculator-button', function () {

                $('.shipping-calculator-form').slideToggle('slow');

                wc_country_select_select2();

                return false;
            });

        }

    })();

    // ================================================================
    // Page Preloader Remove On Page Load
    // ================================================================

    (function () {
        $(window).load(function () {
            $('#page-pre-loader').fadeOut(500, function () {
                $(this).remove();
            });
        });
    }());

    // ================================================================
    // Currency Switcher Redirect
    // ================================================================

    (function () {

        if (hippoJSObject.currency_switcher) {
            $('#currency-switcher').on('change', function () {
                window.location.replace('./?hippo-switch-currency=' + $(this).val());
            });
        }
    }());

});  // end of jquery main wrapper