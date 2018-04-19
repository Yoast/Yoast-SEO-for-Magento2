define([
    "jquery",
    "ko"
], function ($, ko) {
    "use strict";

    var cmsBlockUrl = yoastBoxConfig.cmsBlockUrl;

    return {
        promise: function (field) {
            if (!field || !field.hasOwnProperty("value") || !ko.isObservable(field.value) || !field.value()) {
                return '';
            }

            if (field.oldValue && field.htmlValue && field.oldValue === field.value()) {
                return field.htmlValue;
            }

            return new Promise(function (resolve, reject) {
                $
                    .ajax({
                        url: cmsBlockUrl + "?cms_block_id=" + field.value(),
                        type: "GET",
                        dataType: "json"
                    })
                    .done(function (result) {
                        if (result && result.html || result.html === "") {
                            field.oldValue = field.value();
                            field.htmlValue = result.html;
                            resolve(result.html);
                        } else {
                            reject('invalid result html for cms_block');
                        }
                    })
                    .fail(reject);
            });
        }
    };
});
