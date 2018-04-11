define([
    "jquery",
    "ko",
    "Magento_Ui/js/form/element/abstract",
    "MaxServ_YoastSeo/js/model/entity-configuration-service",
    "MaxServ_YoastSeo/js/model/yoast-data",
    "MaxServ_YoastSeo/js/model/field-manager",
    "MaxServ_YoastSeo/js/model/template-processor",
    "MaxServ_YoastSeo/js/vendor/YoastSeo"
], function ($, ko, Component, configurationService, yoastData, fieldManager, templateProcessor) {
    "use strict";

    var enableMetaKeywords = ko.observable(false);

    return Component.extend({
        defaults: {
            formName: ko.observable(null),
            isTemplateReady: false,
            isConfigurationReady: false,
            hasOtherSettings: ko.computed(function () {
                return enableMetaKeywords();
            }),
            enableMetaKeywords: enableMetaKeywords,
            yoastData: yoastData,
            template: "MaxServ_YoastSeo/yoast-box",
            currentTab: ko.observable('analysis'),
            imports: {
                formData: "${ $.provider }:data"
            }
        },
        initialize: function () {
            this._super();
            this.formName(this.ns);

            configurationService.configuration.subscribe(this.onConfigurationUpdate.bind(this));
            configurationService.load(this.ns);
        },
        onConfigurationUpdate: function () {
            var fieldWrapper = configurationService.configuration().fieldWrapper,
                entityData = this.formData;

            if (fieldWrapper && entityData.hasOwnProperty(fieldWrapper)) {
                entityData = entityData[fieldWrapper];
            }
            this.entityData = entityData;

            console.log(fieldWrapper, entityData);

            yoastData.initEntityData(this.entityData);
            this.initFields();
        },
        onTemplateReady: function () {
            this.initFieldsets();
            this.initPreview();
            this.initApp();
        },
        fieldName: function (field) {
            var wrapper = configurationService.configuration().fieldWrapper;
            if (!wrapper) {
                return field;
            }

            return wrapper + "[" + field + "]";
        },
        initFields: function () {
            var configuration = configurationService.configuration(),
                urlKeyField = configuration.urlKeyField,
                titleField = configuration.titleField,
                metaKeywordField = configuration.metaKeywordField;

            fieldManager.initUrlKeyCreateRedirectField();

            fieldManager.bindFieldToYoastData('url_key', urlKeyField, false);
            fieldManager.bindFieldToYoastData('title', titleField, false);
            fieldManager.bindFieldToYoastData('content_score', 'yoast_content_score', true);
            fieldManager.bindFieldToYoastData('keyword_score', 'yoast_keyword_score', true);
            fieldManager.bindFieldToYoastData('focus_keyword', 'focus_keyword', true);
            fieldManager.bindFieldToYoastData('meta_title', 'meta_title', true);
            fieldManager.bindFieldToYoastData('meta_description', 'meta_description', true);
            fieldManager.bindFieldToYoastData('meta_keywords', metaKeywordField, true);

            fieldManager.hideField('yoast_keyword_score');
            fieldManager.hideField('yoast_content_score');
            fieldManager.hideField('yoast_facebook_title');
            fieldManager.hideField('yoast_facebook_description');
            fieldManager.hideField('yoast_facebook_image');
            fieldManager.hideField('yoast_twitter_title');
            fieldManager.hideField('yoast_twitter_description');
            fieldManager.hideField('yoast_twitter_image');

            templateProcessor.init(this.formData, configurationService.configuration().template);
        },
        initFieldsets: function () {
        },
        initPreview: function () {
            this.previewElement = $("#yoastBox-snippetPreview")[0];
            this.snippetPreview = new YoastSEO.SnippetPreview({
                data: {
                    title: yoastData.getTitle(),
                    keyword: yoastData.focus_keyword(),
                    metaDesc: yoastData.meta_description(),
                    urlPath: yoastData.url_key()
                },
                callbacks: {
                    saveSnippetData: function (data) {
                        yoastData.url_key(data.urlPath);
                        yoastData.meta_title(data.title);
                        yoastData.meta_description(data.metaDesc);

                        this.updateCreateRedirect();
                    }.bind(this)
                },
                baseUrl: yoastData.base_url,
                targetElement: this.previewElement
            });
        },
        updateCreateRedirect: function () {
            var urlKeyField = configurationService.configuration().urlKeyField;
            if (fieldManager.urlKeyCreateRedirectField && fieldManager.urlKeyCreateRedirectField.enabled) {
                fieldManager.urlKeyCreateRedirectField.enabled(
                    yoastData.url_key() !== this.entityData[urlKeyField]
                );
            }
        },
        initApp: function () {
            var locale = yoastBoxConfig.locale;

            this.app = new YoastSEO.App({
                snippetPreview: this.snippetPreview,
                contentAnalysisActive: true,
                locale: locale,
                targets: {
                    output: 'yoastBox-focusKeywordOutput',
                    contentOutput: 'yoastBox-readabilityOutput'
                },
                callbacks: {
                    saveScores: function (score, presenter) {
                        yoastData.keyword_score(score);
                        presenter.renderOverallRating();
                    },
                    saveContentScore: function (score, presenter) {
                        yoastData.content_score(score);
                        presenter.renderOverallRating();
                    },
                    getData: function () {
                        return {
                            baseUrl: yoastData.base_url,
                            title: yoastData.title(),
                            metaTitle: yoastData.meta_title(),
                            keyword: yoastData.focus_keyword(),
                            text: yoastData.content(),
                            meta: yoastData.meta_description(),
                            metaDesc: yoastData.meta_description()
                        }
                    }
                }
            });

            this.app.seoAssessorPresenter.overall = 'yoastBox-overallScore';
            this.app.contentAssessorPresenter.overall = 'yoastBox-readabilityScore';
            this.app.refresh();

            $.each([
                yoastData.title,
                yoastData.content,
                yoastData.url_key,
                yoastData.focus_keyword
            ], function (ignore, observable) {
                observable.subscribe(this.refresh.bind(this));
            }.bind(this));
        },
        refresh: function () {
            this.snippetPreview.setTitle(yoastData.getTitle());
            this.snippetPreview.setUrlPath(yoastData.url_key());
            this.app.refresh();
        },
        setCurrentTab: function (tab) {
            this.currentTab(tab);
        }
    });
});
