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

            return new Promise(function (resolve, reject) {
                $
                    .ajax({
                        url: cmsBlockUrl + "?cms_block_id=" + field.value(),
                        type: "GET",
                        dataType: "json"
                    })
                    .done(function (result) {
                        if (result && result.html || result.html === "") {
                            resolve(result.html);
                        } else {
                            reject();
                        }
                    })
                    .fail(reject);
            });
        }
    };
});
