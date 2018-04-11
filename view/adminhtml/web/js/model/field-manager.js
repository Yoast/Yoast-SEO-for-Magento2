define([
    "jquery",
    "ko",
    "uiRegistry",
    "MaxServ_YoastSeo/js/model/yoast-data"
], function ($, ko, uiRegistry, yoastData) {
    "use strict";

    var fields = {};

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
        setFieldsetTitle: function (fieldIndex) {
            uiRegistry
                .promise({index: fieldIndex})
                .done(function (fieldset) {
                    if (ko.isObservable(fieldset.label)) {
                        fieldset.label('YoastSEO');
                    } else {
                        fieldset.label = ko.observable('YoastSEO');
                    }
                });
        },
        bindFieldToYoastData: function (key, index, hide) {
            if (!fields.hasOwnProperty(key)) {
                uiRegistry
                    .promise({index: index})
                    .done(function (key, hide, field) {
                        if (field) {
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
