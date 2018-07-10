define([
    "jquery",
    "ko",
    "uiRegistry"
], function ($, ko, uiRegistry) {
    "use strict";

    var displayModeField,
        cmsBlockUrl = yoastBoxConfig.cmsBlockUrl;

    return {
        promise: function (field) {
            if (!field || !field.hasOwnProperty("value") || !ko.isObservable(field.value) || !field.value()) {
                return '';
            }

            if (field.oldValue && field.htmlValue && field.oldValue === field.value()) {
                return field.htmlValue;
            }

            return new Promise(function (resolve, reject) {
                function doActualPromise() {
                    var displayMode = displayModeField.value();
                    if (["PAGE", "PRODUCTS_AND_PAGE"].indexOf(displayMode) === -1) {
                        resolve('');
                    } else {
                        $
                            .ajax({
                                url: cmsBlockUrl + "?cms_block_id=" + field.value(),
                                dataType: "json",
                                type: "GET"
                            })
                            .done(function (result) {
                                if (result && result.html) {
                                    field.oldValue = field.value();
                                    field.htmlValue = result.html;
                                    resolve(result.html);
                                } else {
                                    reject();
                                }
                            })
                            .fail(reject);
                    }
                }

                if (!displayModeField) {
                    uiRegistry
                        .promise({index: 'display_mode'})
                        .done(function (uiField) {
                            displayModeField = uiField;
                            displayModeField.value.subscribe(function () {
                                $(document).trigger("yoastseo:template:update");
                            });
                            doActualPromise();
                        });
                } else {
                    doActualPromise();
                }
            });
        }
    };
});
