define([
    "jquery",
    "ko"
], function ($, ko) {
    "use strict";

    var renderUrl = yoastBoxConfig.wysiwygUrl;

    return {
        promise: function (field) {
            if (!field || !field.hasOwnProperty("value") || !ko.isObservable(field.value)) {
                return '';
            }

            return new Promise(function (resolve, reject) {
                $
                    .ajax({
                        url: renderUrl,
                        data: {
                            content: field.value()
                        },
                        type: "POST",
                        dataType: "json"
                    })
                    .done(function (result) {
                        if (result && result.html) {
                            resolve(result.html)
                        } else {
                            reject();
                        }
                    })
                    .fail(reject);
            });
        }
    };
});
