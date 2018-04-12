define([
    "jquery",
    "ko",
    "Magento_Ui/js/form/components/fieldset",
    "MaxServ_YoastSeo/js/model/template-processor",
    "MaxServ_YoastSeo/js/model/yoast-data",
    "MaxServ_YoastSeo/js/vendor/YoastSeo"
], function ($, ko, FieldSet, templateProcessor, yoastData) {
    "use strict";

    return FieldSet.extend({
        defaults: {
            template: "MaxServ_YoastSeo/form/fieldset/yoast-box",
            tabs: ko.observableArray(['analysis', 'facebook', 'twitter', 'other']),
            currentTab: ko.observable('analysis'),
            focus_keyword: yoastData.focus_keyword,
            metaElements: ['meta_title', 'meta_description', 'meta_keywords'],
            imports: {
                formData: "${ $.provider }:data"
            }
        },
        initialize: function () {
            this._super();

            yoastData.init(this.formData);
            templateProcessor.init(yoastData.entityData, yoastData.entityConfig.template);
        },
        onElementRender: function () {
            this.initPreview();
            this.initApplication();
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
                        yoastData.meta_title(data.title);
                        yoastData.meta_description(data.metaDesc);
                        yoastData.url_key(data.urlPath);

                        this.updateCreateRedirect();
                    }.bind(this)
                },
                baseUrl: yoastData.base_url,
                targetElement: this.previewElement
            });
        },
        updateCreateRedirect: function () {
            if (yoastData.fields.url_key && yoastData.fields.url_key.value() !== yoastData.url_key()) {

            }
        },
        initApplication: function () {
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
                yoastData.focus_keyword,
                yoastData.content
            ], function (ignore, field) {
                field.subscribe(this.refreshApp.bind(this));
            }.bind(this));
        },
        refreshApp: function () {
            this.app.refresh();
            this.snippetPreview.setTitle(yoastData.getTitle());
            this.snippetPreview.setUrlPath(yoastData.url_key());
        },
        ucfirst: function (value) {
            return value.substring(0,1).toUpperCase() + value.substring(1);
        },
        isRegularElement: function (elementName) {
            elementName = elementName.split(".").pop();
            if (elementName.indexOf('yoast_') > -1) {
                return false;
            }
            if (this.metaElements.indexOf(elementName) > -1) {
                return false;
            }

            return true;
        },
        isMetaElement: function (elementName) {
            elementName = elementName.split(".").pop();

            if (this.metaElements.indexOf(elementName) === -1) {
                return false;
            }

            return true;
        },
        isElementName: function (elementName, requiredName) {
            elementName = elementName.split(".").pop();

            return elementName === requiredName;
        },
        setCurrentTab: function (tab) {
            this.currentTab(tab);
        }
    });
});
