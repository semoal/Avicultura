jQuery(function ($) {

    var header = $('.header-style-one, .header-style-two, .header-three-sticky, .header-style-four');
    var scrolled = false;

    $(window).scroll(function () {

        if (110 < $(window).scrollTop() && !scrolled) {
            header.addClass('sticky-header animated fadeInDown').animate({'margin-top' : '0px'});
            scrolled = true;
        }

        if (110 > $(window).scrollTop() && scrolled) {
            header.removeClass('sticky-header animated fadeInDown').css('margin-top', '0px');
            scrolled = false;
        }
    });
});