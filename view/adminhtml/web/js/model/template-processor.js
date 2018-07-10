define([
    "jquery",
    "ko",
    "uiRegistry",
    "MaxServ_YoastSeo/js/model/yoast-data",
    "MaxServ_YoastSeo/js/form/field/reader/text",
    "MaxServ_YoastSeo/js/form/field/reader/wysiwyg",
    "MaxServ_YoastSeo/js/form/field/reader/cms_block",
    "MaxServ_YoastSeo/js/form/field/reader/category_landing_page",
    "MaxServ_YoastSeo/js/form/provider/product_images"
], function (
    $,
    ko,
    uiRegistry,
    yoastData,
    textReader,
    wysiwygReader,
    cmsBlockReader,
    categoryLandingPageReader,
    productImagesProvider
) {
    "use strict";

    return {
        readers: {
            text: textReader,
            wysiwyg: wysiwygReader,
            cms_block: cmsBlockReader,
            category_landing_page: categoryLandingPageReader
        },
        providers: {
            product_images: productImagesProvider
        },
        elements: {},
        template: '',
        formData: {},
        registerReader: function (type, reader) {
            this.readers[type] = reader;
        },
        registerProvider: function (type, provider) {
            this.providers[type] = provider;
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
                        index: index
                    };

                this.findField(element, index);

                if (attributes) {
                    $.each(attributes.split(' '), function (ignore, attribute) {
                        var attributeConfig = new RegExp("([a-z]+)='([^']+)'", "i").exec(attribute);
                        if (attributeConfig.length == 3) {
                            element[attributeConfig[1]] = attributeConfig[2];
                        }
                    });
                }

                this.elements[index] = element;
            }.bind(this));
            
            $(document).on('yoastseo:template:update', this.scheduleUpdate.bind(this));
            
            this.update();
        },
        scheduleUpdate: function () {
            if (this.updateTimer) {
                clearTimeout(this.updateTimer);
            }
            this.updateTimer = setTimeout(this.update.bind(this), 500);
        },
        update: function () {
            var content = this.template,
                inputs = [],
                promises = [];

            $.each(this.elements, function (ignore, element) {
                inputs.push(element.input);

                switch (true) {
                    case (element.field && element.reader && this.readers.hasOwnProperty(element.reader)):
                        promises.push(this.readers[element.reader].promise(element.field));
                        break;
                    case (element.field && element.field.value):
                        promises.push(element.field.value());
                        break;
                    case (this.formData.hasOwnProperty(element.index)):
                        promises.push(this.formData[element.index]);
                        break;
                    case (element.hasOwnProperty('default')):
                        promises.push(element.default);
                        break;
                    case (element.hasOwnProperty('provider') && this.providers.hasOwnProperty(element.provider)):
                        promises.push(this.providers[element.provider].promise());
                        break;
                    default:
                        promises.push('');
                        break;
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

            yoastData.content(content);
        },
        findField: function (element, index) {
            uiRegistry
                .promise({index: index})
                .done(function (field) {
                    if (field && field.value && ko.isObservable(field.value)) {
                        element.field = field;
                        field.value.subscribe(this.scheduleUpdate.bind(this));
                        this.scheduleUpdate();
                    }
                }.bind(this));
        }
    };
});
