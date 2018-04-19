define([
    "jquery",
    "ko",
    "uiRegistry"
], function ($, ko, uiRegistry) {
    "use strict";

    var formData = uiRegistry.get("product_form.product_form_data_source");

    uiRegistry
        .promise({index: 'gallery'})
        .done(function (field) {
            field.on("update", function () {
                $(document).trigger("yoastseo:template:update");
            });
        });

    return {
        promise: function () {
            var html = '',
                images = [];

            $("[data-role='image'] .product-image").each(function (ignore, image) {
                images.push(image.outerHTML);
            });

            if (!images.length && formData.data.product.media_gallery.images) {
                $.each(formData.data.product.media_gallery.images, function (ignore, img) {
                    var src = yoastBoxConfig.productImageBaseUrl + img.file,
                        alt = img.label;
                    images.push("<img src='" + src + "' alt='" + alt + "' />");
                });
            }

            images.forEach(function (img) {
                html += img + "\n";
            });

            return html;
        }
    };
});
