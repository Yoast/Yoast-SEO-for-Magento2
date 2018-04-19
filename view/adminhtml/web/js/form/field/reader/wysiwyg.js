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

            if (field.oldValue && field.htmlValue && field.oldValue === field.value()) {
                return field.htmlValue;
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
                        if (result && result.html || result.html === "") {
                            field.oldValue = field.value();
                            field.htmlValue = result.html;

                            resolve(result.html)
                        } else {
                            reject('wysiwyg field not found');
                        }
                    })
                    .fail(reject);
            });
        }
    };
});
