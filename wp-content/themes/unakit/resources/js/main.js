"use strict";

jQuery(function ($) {
    const pageBody = $("html");

    // Menu Toggle

    const menuToggle = $("input#nav-toggle-input");
    const menuToggleLabel = $("label.nav-toggle");
    const menuContainer = $("nav.header-menu");

    $(".nav-close-area").on("click", function (e) {
        menuToggle.trigger('click');
        e.preventDefault();
    });

    menuToggle.on('change', function (e) {
        if (menuToggle.is(':checked')) {
            pageBody.addClass('no-scroll-m');
        } else {
            pageBody.removeClass('no-scroll-m');
        }
    });

    // Submenus

    // Allow pressing enter/return to toggle submenu
    $(".submenu-toggle__checkbox").on('keypress', function (e) {
        // Enter opens submenu
        if (e.key == "Enter") {
            $(e.target).trigger('click');
            e.preventDefault;
        }
    });

    // Apply aria-expanded attribute on state change
    $(".submenu-toggle__checkbox").on('click', function (e) {
        $(e.target).attr('aria-expanded', $(e.target).is(':checked'));
    });

    $(".submenu-toggle__checkbox").attr('role', 'button');

    // Detect Swiping

    /**
     * jquery.detectSwipe v2.1.3
     * jQuery Plugin to obtain touch gestures from iPhone, iPod Touch, iPad and Android
     * http://github.com/marcandre/detect_swipe
     * Based on touchwipe by Andreas Waltl, netCU Internetagentur (http://www.netcu.de)
     */

    (function (factory) {
        if (typeof define === 'function' && define.amd) {
            // AMD. Register as an anonymous module.
            define(['jquery'], factory);
        } else if (typeof exports === 'object') {
            // Node/CommonJS
            module.exports = factory(require('jquery'));
        } else {
            // Browser globals
            factory(jQuery);
        }
    }(function ($) {

        $.detectSwipe = {
            version: '2.1.2',
            enabled: 'ontouchstart' in document.documentElement,
            preventDefault: true,
            threshold: 20
        };

        let startX,
            startY,
            isMoving = false;

        function onTouchEnd() {
            this.removeEventListener('touchmove', onTouchMove);
            this.removeEventListener('touchend', onTouchEnd);
            isMoving = false;
        }

        function onTouchMove(e) {
            if ($.detectSwipe.preventDefault) { e.preventDefault(); }
            if (isMoving) {
                let x = e.touches[0].pageX;
                let y = e.touches[0].pageY;
                let dx = startX - x;
                let dy = startY - y;
                let dir;
                let ratio = window.devicePixelRatio || 1;
                if (Math.abs(dx) * ratio >= $.detectSwipe.thresholdh) {
                    dir = dx > 0 ? 'left' : 'right';
                }
                else if (Math.abs(dy) * ratio >= $.detectSwipe.thresholdv) {
                    dir = dy > 0 ? 'up' : 'down';
                }
                if (dir) {
                    onTouchEnd.call(this);
                    $(this).trigger('swipe', dir).trigger('swipe' + dir);
                }
            }
        }

        function onTouchStart(e) {
            if (e.touches.length === 1) {
                startX = e.touches[0].pageX;
                startY = e.touches[0].pageY;
                isMoving = true;
                this.addEventListener('touchmove', onTouchMove, false);
                this.addEventListener('touchend', onTouchEnd, false);
            }
        }

        function setup() {
            this.addEventListener && this.addEventListener('touchstart', onTouchStart, false);
        }

        function teardown() {
            this.removeEventListener('touchstart', onTouchStart);
        }

        $.event.special.swipe = { setup: setup };

        $.each(['left', 'up', 'down', 'right'], function () {
            $.event.special['swipe' + this] = {
                setup: function () {
                    $(this).on('swipe', $.noop);
                }
            };
        });
    }));
    /**
    * jquery.detectSwipe v2.1.3 end
    */

    $.detectSwipe.thresholdh = 120;
    $.detectSwipe.thresholdv = 300;
    $.detectSwipe.preventDefault = false;

    const menuSwipeTarget = $('.nav-close-area, nav.header-menu, .nav-toggle');

    menuSwipeTarget.on('swiperight', function (e) {
        menuToggle.trigger('click');
        e.preventDefault();
    });
});