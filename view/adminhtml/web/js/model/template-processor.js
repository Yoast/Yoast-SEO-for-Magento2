define([
    "jquery",
    "uiRegistry",
    "MaxServ_YoastSeo/js/model/yoast-data"
], function ($, uiRegistry, yoastData) {
    "use strict";

    return {
        elements: {},
        template: '',
        formData: {},
        init: function (formData, template) {
            var regex = new RegExp("{{([a-z_]+)\\s?((?:\\s?[a-z]+='[^']+')?)}}"),
                inputs = template.match(/{{[a-z_]+\s?(?:\s?[a-z]+='[^']+')?}}/gm);

            this.formData = formData;
            this.template = template;

            $.each(inputs, function (ignore, input) {
                var config = regex.exec(input),
                    index = config[1],
                    attributes = config.length >= 3 ? config[2] : false,
                    element = {
                        input: input,
                        index: index,
                        field: this.findField(index)
                    };

                if (attributes) {
                    $.each(attributes.split(' '), function (ignore, attribute) {
                        var attributeConfig = new RegExp("([a-z]+)='([^']+)'", "i").exec(attribute);
                        if (attributeConfig.length == 3) {
                            element[attributeConfig[1]] = attributeConfig[2];
                        }
                    });
                }

                if (element.field && element.field.value) {
                    element.field.value.subscribe(this.update.bind(this));
                }

                this.elements[index] = element;
            }.bind(this));
            this.update();
        },
        update: function () {
            var content = this.template;

            $.each(this.elements, function (ignore, element) {
                var value = '';
                if (!element.field) {
                    var field = this.findField(element.index);
                    if (field) {
                        element.field = field;
                    }
                }

                if (element.field && element.field.value) {
                    value = element.field.value();
                } else if (this.formData.hasOwnProperty(element.index)) {
                    value = this.formData[element.index];
                } else if (element.hasOwnProperty('default')) {
                    value = element.default;
                }

                content = content.replace(element.input, value);
            }.bind(this));

            yoastData.content(content);
        },
        findField: function (index) {
            var field;

            field = uiRegistry.get({dataScope: "data." + index});

            if (!field) {
                field = uiRegistry.get({index: index});
            }

            if (!field) {
                field = uiRegistry.get({name: index});
            }

            return field;
        }
    };
});
