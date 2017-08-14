var Botble = Botble || {};

Botble.blockUI = function (options) {
    options = $.extend(true, {}, options);
    var html = '';
    if (options.animate) {
        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
    } else if (options.iconOnly) {
        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="/vendor/core/images/loading-spinner-blue.gif" align=""></div>';
    } else if (options.textOnly) {
        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
    } else {
        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="/vendor/core/images/loading-spinner-blue.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
    }

    if (options.target) { // element blocking
        var el = $(options.target);
        if (el.height() <= ($(window).height())) {
            options.cenrerY = true;
        }
        el.block({
            message: html,
            baseZ: options.zIndex ? options.zIndex : 1000,
            centerY: options.cenrerY !== undefined ? options.cenrerY : false,
            css: {
                top: '10%',
                border: '0',
                padding: '0',
                backgroundColor: 'none'
            },
            overlayCSS: {
                backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                opacity: options.boxed ? 0.05 : 0.1,
                cursor: 'wait'
            }
        });
    } else { // page blocking
        $.blockUI({
            message: html,
            baseZ: options.zIndex ? options.zIndex : 1000,
            css: {
                border: '0',
                padding: '0',
                backgroundColor: 'none'
            },
            overlayCSS: {
                backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                opacity: options.boxed ? 0.05 : 0.1,
                cursor: 'wait'
            }
        });
    }
};

Botble.unblockUI = function (target) {
    if (target) {
        $(target).unblock({
            onUnblock: function () {
                $(target).css('position', '');
                $(target).css('zoom', '');
            }
        });
    } else {
        $.unblockUI();
    }
};

Botble.showNotice = function (messageType, message, messageHeader) {
    toastr.options = {
        closeButton: true,
        positionClass: 'toast-bottom-right',
        onclick: null,
        showDuration: 1000,
        hideDuration: 1000,
        timeOut: 10000,
        extendedTimeOut: 1000,
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'

    };
    toastr[messageType](message, messageHeader);
};

Botble.handleError = function (data) {
    if (typeof (data.responseJSON) != 'undefined') {
        if (typeof (data.responseJSON.message) != 'undefined') {
            Botble.showNotice('error', data.responseJSON.message, Botble.languages.notices_msg.error);
        } else {
            $.each(data.responseJSON, function (index, el) {
                $.each(el, function (key, item) {
                    Botble.showNotice('error', item, Botble.languages.notices_msg.error);
                });
            });
        }
    } else {
        Botble.showNotice('error', data.statusText, Botble.languages.notices_msg.error);
    }
};

Botble.countCharacter = function () {
    $.fn.charCounter = function (max, settings) {
        max = max || 100;
        settings = $.extend({
            container: '<span></span>',
            classname: 'charcounter',
            format: '(%1 ' + Botble.languages.system.character_remain + ')',
            pulse: true,
            delay: 0
        }, settings);
        var p, timeout;

        function count(el, container) {
            el = $(el);
            if (el.val().length > max) {
                el.val(el.val().substring(0, max));
                if (settings.pulse && !p) {
                    pulse(container, true);
                }
            }
            if (settings.delay > 0) {
                if (timeout) {
                    window.clearTimeout(timeout);
                }
                timeout = window.setTimeout(function () {
                    container.html(settings.format.replace(/%1/, (max - el.val().length)));
                }, settings.delay);
            } else {
                container.html(settings.format.replace(/%1/, (max - el.val().length)));
            }
        }

        function pulse(el, again) {
            if (p) {
                window.clearTimeout(p);
                p = null;
            }
            el.animate({
                opacity: 0.1
            }, 100, function () {
                $(this).animate({
                    opacity: 1.0
                }, 100);
            });
            if (again) {
                p = window.setTimeout(function () {
                    pulse(el)
                }, 200);
            }
        }

        return this.each(function () {
            var container;
            if (!settings.container.match(/^<.+>$/)) {
                // use existing element to hold counter message
                container = $(settings.container);
            } else {
                // append element to hold counter message (clean up old element first)
                $(this).next("." + settings.classname).remove();
                container = $(settings.container)
                    .insertAfter(this)
                    .addClass(settings.classname);
            }
            $(this)
                .unbind('.charCounter')
                .bind('keydown.charCounter', function () {
                    count(this, container);
                })
                .bind('keypress.charCounter', function () {
                    count(this, container);
                })
                .bind('keyup.charCounter', function () {
                    count(this, container);
                })
                .bind('focus.charCounter', function () {
                    count(this, container);
                })
                .bind('mouseover.charCounter', function () {
                    count(this, container);
                })
                .bind('mouseout.charCounter', function () {
                    count(this, container);
                })
                .bind('paste.charCounter', function () {
                    var me = this;
                    setTimeout(function () {
                        count(me, container);
                    }, 10);
                });
            if (this.addEventListener) {
                this.addEventListener('input', function () {
                    count(this, container);
                }, false);
            }
            count(this, container);
        });
    };

    $(document).on('click', 'input[data-counter], textarea[data-counter]', function () {
        $(this).charCounter($(this).data('counter'), {
            container: '<small></small>'
        });
    });
};

Botble.manageSidebar = function () {

    var body = $('body');
    var navigation = $('.navigation');
    var sidebar_content = $('.sidebar-content');

    navigation.find('li.active').parents('li').addClass('active');
    navigation.find('li').not('.active').has('ul').children('ul').addClass('hidden-ul');
    navigation.find('li').has('ul').children('a').parent('li').addClass('has-ul');


    $(document).on('click', '.sidebar-toggle', function (e) {
        e.preventDefault();

        body.toggleClass('sidebar-narrow');

        if (body.hasClass('sidebar-narrow')) {
            navigation.children('li').children('ul').css('display', '');

            sidebar_content.hide().delay().queue(function () {
                $(this).show().addClass('animated fadeIn').clearQueue();
            });
        } else {
            navigation.children('li').children('ul').css('display', 'none');
            navigation.children('li.active').children('ul').css('display', 'block');

            sidebar_content.hide().delay().queue(function () {
                $(this).show().addClass('animated fadeIn').clearQueue();
            });
        }
    });


    navigation.find('li').has('ul').children('a').on('click', function (e) {
        e.preventDefault();

        if (body.hasClass('sidebar-narrow')) {
            $(this).parent('li > ul li').not('.disabled').toggleClass('active').children('ul').slideToggle(250);
            $(this).parent('li > ul li').not('.disabled').siblings().removeClass('active').children('ul').slideUp(250);
        } else {
            $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(250);
            $(this).parent('li').not('.disabled').siblings().removeClass('active').children('ul').slideUp(250);
        }
    });

    $(document).on('click', '.offcanvas', function () {
        $('body').toggleClass('offcanvas-active').toggleClass('sidebar-narrow');
    });
};

Botble.manageTableAction = function () {
    $('.group-checkable').uniform();

    $('#admin_form').on('click', '.task-item', function (event) {
        event.preventDefault();
        var check = true;

        $('#task').val($(this).data('task'));

        if ($(this).data('task') === 'delete') {
            check = confirm(Botble.languages.datatables.confirm_delete_msg);
        }

        if (check) {
            $('#admin_form').submit();
        }
    });
};

Botble.initDatepicker = function (element) {
    if (jQuery().datepicker) {
        $(document).find(element).datepicker({
            maxDate: 0,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
            autoclose: true,
        });
    }
};

Botble.initResources = function () {
    if (jQuery().select2) {
        $(document).find('.select-multiple').select2({
            width: '100%',
            allowClear: true,
            placeholder: $(this).data('placeholder')
        });
        $(document).find('.select-search-full').select2({
            width: '100%'
        });
        $(document).find('.select-full').select2({
            width: '100%',
            minimumResultsForSearch: -1
        });
    }

    this.initDatepicker('.datepicker');

    if (jQuery().fancybox) {
        $('.iframe-btn').fancybox({
            'width': '900px',
            'height': '700px',
            'type': 'iframe',
            'autoScale': false,
            openEffect: 'none',
            closeEffect: 'none',
            overlayShow: true,
            overlayOpacity: 0.7
        });
        $('.fancybox').fancybox({
            openEffect: 'none',
            closeEffect: 'none',
            overlayShow: true,
            overlayOpacity: 0.7,
            helpers: {
                media: {}
            }
        });
    }
    $('.styled').uniform();
    $('.tip').tooltip({placement: 'top'});

    if (jQuery().areYouSure) {
        $('form').areYouSure();
    }

    Botble.callScroll($('.list-item-checkbox'));
};

Botble.callScroll = function (obj) {
    obj.mCustomScrollbar({
        axis: "yx",
        theme: "minimal-dark",
        scrollButtons: {
            enable: true
        },
        callbacks: {
            whileScrolling: function () {
                obj.find('.tableFloatingHeaderOriginal').css({
                    'top': -this.mcs.top + 'px'
                });
            }
        }
    });
    obj.stickyTableHeaders({scrollableArea: obj, "fixedOffset": 2});
};

Botble.handleWaypoint = function () {
    if ($('#waypoint').length > 0) {
        new Waypoint({
            element: document.getElementById('waypoint'),
            handler: function(direction) {
                if (direction == 'down') {
                    $('.form-actions-fixed-top').removeClass('hidden');
                } else {
                    $('.form-actions-fixed-top').addClass('hidden');
                }
            }
        });
    }
};

// Handles counterup plugin wrapper
Botble.handleCounterup = function () {
    if (!$().counterUp) {
        return;
    }

    $("[data-counter='counterup']").counterUp({
        delay: 10,
        time: 1000
    });
};

$(document).ready(function () {
    Botble.countCharacter();
    Botble.manageSidebar();
    Botble.manageTableAction();
    Botble.initResources();
    Botble.handleWaypoint();
    Botble.handleCounterup();
});
