var search_input = $('.search-input');
var super_search = $('.super-search');
var close_search = $('.close-search');
var search_result = $('.search-result');
var timeoutID = null;

var Ripple = {
    searchFunction: function (keyword) {
        clearTimeout(timeoutID);
        timeoutID = setTimeout(function () {
            super_search.removeClass('search-finished');
            search_result.fadeOut();
            $.ajax({
                type: 'GET',
                cache: false,
                url: '/api/search',
                data: {
                    'q': keyword
                },
                success: function (res) {
                    if (!res.error) {
                        var html = '<p class="search-result-title">Search from: </p>';
                        $.each(res.data.items, function (index, el) {
                            html += '<p class="search-item">' + index + '</p>';
                            html += el;
                        });
                        html += '<div class="clearfix"></div>';
                        search_result.html(html);
                        super_search.addClass('search-finished');
                    } else {
                        search_result.html(res.message);
                    }
                    search_result.fadeIn(500);
                },
                error: function (res) {
                    search_result.html(res.responseText);
                    search_result.fadeIn(500);
                }
            });
        }, 500);
    },
    bindActionToElement: function () {
        close_search.on('click', function (event) {
            event.preventDefault();
            if (close_search.hasClass('active')) {
                super_search.removeClass('active');
                search_result.hide();
                close_search.removeClass('active');
                $('body').removeClass('overflow');
                $('.quick-search > .form-control').focus();
            } else {
                super_search.addClass('active');
                if (search_input.val() != '') {
                    Ripple.searchFunction(search_input.val());
                }
                $('body').addClass('overflow');
                close_search.addClass('active');
            }
        });

        search_input.keyup(function (e) {
            search_input.val(e.target.value);
            Ripple.searchFunction(e.target.value);

        });
    },
    initGallery: function () {
        var container = document.querySelector('#list-photo');
        var masonry;
        // initialize Masonry after all images have loaded
        if (container) {
            imagesLoaded(container, function () {
                masonry = new Masonry(container);
            });
        }

        $('#list-photo').lightGallery({
            loop: true,
            thumbnail: true,
            fourceAutoply: false,
            autoplay: false,
            pager: false,
            speed: 300,
            scale: 1,
            keypress: true
        });

        $(document).on('click', '.lg-toogle-thumb', function () {
            $(document).find('.lg-sub-html').toggleClass('inactive');
        });
    },
    Owlcarousel: function () {
        $("[data-slider='owl'] .owl-carousel").each(function () {
            var parent = $(this).parent();

            var items;
            var itemsDesktop;
            var itemsDesktopSmall;
            var itemsTablet;
            var itemsTabletSmall;
            var itemsMobile;

            if (parent.data('single-item') == 'true') {
                items = 1;
                itemsDesktop = 1;
                itemsDesktopSmall = 1;
                itemsTablet = 1;
                itemsTabletSmall = 1;
                itemsMobile = 1;
            } else {
                items = parent.data('items');
                itemsDesktop = [1199, parent.data('desktop-items') ? parent.data('desktop-items') : items];
                itemsDesktopSmall = [979, parent.data('desktop-small-items') ? parent.data('desktop-small-items') : 3];
                itemsTablet = [768, parent.data('tablet-items') ? parent.data('tablet-items') : 2];
                itemsMobile = [479, parent.data('mobile-items') ? parent.data('mobile-items') : 1];
            }

            $(this).owlCarousel({

                items: items,
                itemsDesktop: itemsDesktop,
                itemsDesktopSmall: itemsDesktopSmall,
                itemsTablet: itemsTablet,
                itemsTabletSmall: itemsTablet,
                itemsMobile: itemsMobile,

                navigation: parent.data('navigation') ? true : false,
                navigationText: false,
                slideSpeed: parent.data('slide-speed'),
                paginationSpeed: parent.data('pagination-speed'),
                singleItem: parent.data('single-item') ? true : false,
                autoPlay: parent.data('auto-play')
            });
        });
    }
};

$(document).ready(function () {
    Ripple.bindActionToElement();
    Ripple.initGallery();
    Ripple.Owlcarousel();
});
