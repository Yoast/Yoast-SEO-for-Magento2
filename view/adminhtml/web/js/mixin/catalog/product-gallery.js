define([
    "jquery"
], function ($) {
    "use strict";

    return function (widget) {
        $.widget("mage.productGallery", widget, {
            options: {
                parentComponent: 'index = gallery'
            }
        });

        return $.mage.productGallery;
    };
});
