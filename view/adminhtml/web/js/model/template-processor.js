define([
    "jquery",
    "uiRegistry",
    "MaxServ_YoastSeo/js/model/yoast-data",
    "MaxServ_YoastSeo/js/form/field/reader/text",
    "MaxServ_YoastSeo/js/form/field/reader/wysiwyg"
], function ($, uiRegistry, yoastData, textReader, wysiwygReader) {
    "use strict";

    return {
        readers: {
            text: textReader,
            wysiwyg: wysiwygReader
        },
        elements: {},
        template: '',
        formData: {},
        registerReader: function (type, reader) {
            this.readers[type] = reader;
        },
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
            var content = this.template,
                inputs = [],
                promises = [];

            $.each(this.elements, function (ignore, element) {
                inputs.push(element.input);

                if (element.reader && this.readers.hasOwnProperty(element.reader)) {
                    promises.push(this.readers[element.reader].promise(element.field));
                } else if (element.field && element.field.value) {
                    promises.push(element.field.value());
                } else if (this.formData.hasOwnProperty(element.index)) {
                    promises.push(this.formData[element.index]);
                } else if (element.hasOwnProperty('default')) {
                    promises.push(element.default);
                } else {
                    promises.push('');
                }

            }.bind(this));

            Promise
                .all(promises)
                .then(function (values) {
                    for (var i = 0, len = inputs.length; i < len; i++) {
                        var input = inputs[i],
                            value = values[i];
                        content = content.replace(input, value);
                    }

                    yoastData.content(content);
                }, function(error) {
                    console.log('error', error);
                });

            //yoastData.content(content);
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
