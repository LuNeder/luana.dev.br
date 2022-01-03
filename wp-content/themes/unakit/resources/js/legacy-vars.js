/**
 * Deriveds from code provided by John Hildenbiddle as an addition to css-vars-ponyfill
 * css-vars-ponyfill - JS Ponyfill
 * Copyright 2018-2020, John Hildenbiddle - https://jhildenbiddle.github.io/css-vars-ponyfill/
 * Licensed under MIT License - https://github.com/jhildenbiddle/css-vars-ponyfill/blob/master/LICENSE
 */

"use strict";

if (typeof Object.assign != 'function') {
    // Must be writable: true, enumerable: false, configurable: true
    Object.defineProperty(Object, "assign", {
        value: function assign(target, varArgs) { // .length of function is 2
            'use strict';
            if (target == null) { // TypeError if undefined or null
                throw new TypeError('Cannot convert undefined or null to object');
            }

            var to = Object(target);

            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];

                if (nextSource != null) { // Skip over if undefined or null
                    for (var nextKey in nextSource) {
                        // Avoid bugs when hasOwnProperty is shadowed
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        },
        writable: true,
        configurable: true
    });
}

// Simple debounce timer
let resizeTimer;

// Define CSS custom properties grouped by media query
const mqVars = {
    "(min-width: 540px)": {
        "--container_width": "540px",
        "--container_width_aligned": "calc((540px * 0.5) - 20px)",
        "--container_width_wide": "calc(540px + 12rem)",
    },
    "(min-width: 768px)": {
        "--container_width": "540px",
        "--container_width_aligned": "calc((540px * 0.5) - 20px)",
        "--container_width_wide": "calc(540px + 12rem)",
    },
    "(min-width: 992px)": {
        "--container_width": "710px",
        "--container_width_aligned": "calc((710px * 0.5) - 20px)",
        "--container_width_wide": "calc(710px + 12rem)",
    },
    "(min-width: 1200px)": {
        "--container_width": "780px",
        "--container_width_aligned": "calc((780px * 0.5) - 20px)",
        "--container_width_wide": "calc(780px + 12rem)",
    },
}

// Returns custom properties based on active media queries
function getActiveVars() {
    const activeVars = {};
    //let activeVars = {};

    // Get custom property values for all active media queries
    for (let key in mqVars) {
        if (window.matchMedia(key).matches) {
            Object.assign(activeVars, mqVars[key]);
        }
    }

    return activeVars;
}

// Call ponyfill with default variables
cssVars({
    variables: getActiveVars()
});

// Create event listener to detect media query changes
window.addEventListener('resize', function (e) {
    clearTimeout(resizeTimer);

    // Call ponyfill
    resizeTimer = setTimeout(function () {
        cssVars({
            variables: getActiveVars()
        });
    }, 200);
});


cssVars({
    include: 'style,link[rel="stylesheet"][href*="themes"]' // Ponyfill for inline and theme styles
});