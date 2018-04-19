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
                                    resolve(result.html);
                                } else {
                                    reject();
                                }
                            })
                            .fail(reject);
                    }
                }

                if (!displayModeField) {
                    console.log('search display mode field');
                    uiRegistry
                        .promise({index: 'display_mode'})
                        .done(function (uiField) {
                            console.log('display mode field found');
                            displayModeField = uiField;
                            displayModeField.value.subscribe(function () {
                                console.log('display mode changed');
                                $(document).trigger("yoastseo:reload");
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
