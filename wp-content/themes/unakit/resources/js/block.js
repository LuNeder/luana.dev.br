"use strict";

// Core / Group
wp.blocks.registerBlockStyle('core/group', {
    name: 'card',
    label: wp.i18n.__('Card', 'unakit'),
});
wp.blocks.registerBlockStyle('core/group', {
    name: 'overflow-bottom',
    label: wp.i18n.__('Overflow Bottom', 'unakit'),
});

// Core / Columns
wp.blocks.registerBlockStyle('core/columns', {
    name: 'reverse-on-large',
    label: wp.i18n.__('Reverse on large screen', 'unakit'),
});
wp.blocks.registerBlockStyle('core/columns', {
    name: 'gapless',
    label: wp.i18n.__('Gapless', 'unakit'),
});

// Core / List
wp.blocks.registerBlockStyle('core/list', {
    name: '2col',
    label: wp.i18n.__('2 Columns', 'unakit'),
});
wp.blocks.registerBlockStyle('core/list', {
    name: '3col',
    label: wp.i18n.__('3 Columns', 'unakit'),
});

// Image
wp.blocks.registerBlockStyle('core/image', {
    name: 'box-shadow',
    label: wp.i18n.__('Box Shadow', 'unakit'),
});
wp.blocks.registerBlockStyle('core/image', {
    name: 'drop-shadow',
    label: wp.i18n.__('Drop Shadow', 'unakit'),
});