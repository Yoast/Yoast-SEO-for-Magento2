define([
    "jquery",
    "ko",
    "uiRegistry"
], function ($, ko, uiRegistry) {
    "use strict";

    var focusKeywordElement;

    return {
        keyword_score: ko.observable(null),
        content_score: ko.observable(null),
        focus_keyword: ko.observable(null),
        title: ko.observable(null),
        url_key: ko.observable(null),
        meta_title: ko.observable(null),
        meta_description: ko.observable(null),
        content: ko.observable(null),
        getTitle: function () {
            if (this.meta_title()) {
                return this.meta_title();
            } else if (this.title()) {
                return this.title();
            }

            return '';
        },
        fields: {},

        init: function (formData) {
            this.initData(formData);
            this.initFields();
        },
        initData: function (formData) {
            var entityData = formData,
                fieldWrapper,
                entityConfig = yoastBoxConfig.entity;

            if (entityConfig.hasOwnProperty('fieldWrapper')) {
                fieldWrapper = entityConfig.fieldWrapper;
            }

            if (fieldWrapper && formData.hasOwnProperty(fieldWrapper)) {
                entityData = formData[fieldWrapper];
            }

            this.entityConfig = entityConfig;
            this.entityData = entityData;
        },
        initFields: function () {
            uiRegistry
                .promise({index: 'yoast_focus_keyword'})
                .done(function (field) {
                    focusKeywordElement = field;
                }.bind(this));

            $.each({
                keyword_score: 'yoast_keyword_score',
                content_score: 'yoast_content_score',
                focus_keyword: 'yoast_focus_keyword',
                title: this.entityConfig.titleField,
                url_key: this.entityConfig.urlKeyField,
                meta_title: 'meta_title',
                meta_description: 'meta_description'
            }, function (key, fieldIndex) {
                uiRegistry
                    .promise({index: fieldIndex})
                    .done(function (key, field) {
                        this.fields[key] = field;
                        this[key](field.value());

                        field.value.subscribe(function (key, field) {
                            this[key](field.value());
                        }.bind(this, key, field));

                        this[key].subscribe(function (key, field) {
                            field.value(this[key]());
                        }.bind(this, key, field));

                    }.bind(this, key))
            }.bind(this))
        },
        get base_url() {
            var url = yoastBoxConfig.baseUrl,
                path;

            if (this.entityData && this.entityData.hasOwnProperty('url_path')) {
                path = this.entityData.url_path;
                path = path.explode('/').slice(0, -1).join('/');
                if (path) {
                    url += path + "/";
                }
            }

            return url;
        }
    };
});
