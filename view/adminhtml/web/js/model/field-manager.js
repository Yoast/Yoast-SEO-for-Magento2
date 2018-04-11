define([
    "jquery",
    "ko",
    "uiRegistry",
    "MaxServ_YoastSeo/js/model/yoast-data"
], function ($, ko, uiRegistry, yoastData) {
    "use strict";

    var fields = {},
        urlKeyCreateRedirectField;

    $.each(yoastData, function (key, observable) {
        if (!ko.isObservable(observable)) {
            return;
        }

        observable.subscribe(function () {
            if (fields.hasOwnProperty(key)) {
                fields[key].value(observable()).trigger('keyup');
            }
        });
    });

    return {
        urlKeyCreateRedirectField: urlKeyCreateRedirectField,
        initUrlKeyCreateRedirectField: function () {
            uiRegistry
                .promise({index: 'url_key_create_redirect'})
                .done(function (field) {
                    if (field) {
                        urlKeyCreateRedirectField = field;
                    }
                });
        },
        hideField: function (index) {
            uiRegistry
                .promise({index: index})
                .done(function (field) {
                    if (field) {
                        field.visible(false);
                    }
                });
        },
        bindFieldToYoastData: function (key, index, hide) {
            if (!fields.hasOwnProperty(key)) {
                uiRegistry
                    .promise({index: index})
                    .done(function (key, hide, field) {
                        console.log(key, hide, field);
                        if (field && field.value) {
                            fields[key] = field;
                            if (yoastData.hasOwnProperty(key)) {
                                yoastData[key](field.value());
                            }
                            field.value.subscribe(function (newValue) {
                                if (yoastData.hasOwnProperty(key)) {
                                    yoastData[key](newValue);
                                }
                            });
                            if (hide) {
                                field.visible(false);
                            }
                        }
                    }.bind(null, key, hide));
            }
        }
    };
});
