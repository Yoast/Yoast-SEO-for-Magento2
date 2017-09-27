/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0).
 * This license is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/gpl-3.0.en.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    Maxserv: MaxServ_YoastSeo
 * @package     Maxserv: MaxServ_YoastSeo
 * @author      Vincent Hornikx <vincent.hornikx@maxserv.com>
 * @copyright   Copyright (c) 2017 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */
/* global YoastSEO, window */

define([
    'jquery',
    'uiRegistry',
    'mage/apply/main',
    'domReady!'
], function($, registry, mage) {
    "use strict";

    $.widget('maxServ.yoastSeo', {
        options: {
            provider: false
        },
        _create: function() {
            mage.apply();
            this.config = window.yoastBoxConfig;

            this.source = registry.get(this.config.provider);
            if (this.config.providerElement) {
                this.sourceData = this.source.data[this.config.providerElement];
            } else {
                this.sourceData = this.source.data;
            }

            this.setup();
        },
        setup: function() {
            this.element = $('#yoast-seo-wrapper');

            this.snippetPreviewElement = $('#yoast-seo-snippet-preview')[0];
            this.getInputElements();
            $('#yoast-seo-wrapper').tabs({
                active: 0
            });

            this.inputElements = [
                this.titleInputElement,
                this.focusKeywordInputElement
            ];

            this.hideInputElements = [
                this.urlKeyInputElement,
                this.metaTitleInputElement,
                this.metaDescriptionInputElement
            ];

            if (!this.config.isKeywordsEnabled) {
                this.hideInputElements.push(this.metaKeywordsInputElement);
            }

            this.getTemplateElements();
            this.setupSnippetPreview();
            this.setupApp();
            this.updateFieldsets();
            this.setupEventListeners();

            /*this.updateInterval = window.setInterval(function () {
                this.update();
            }.bind(this), 1000);*/
        },
        getFocusKeywordFieldIdentifier: function() {
            return this.config.focusKeywordFieldIdentifier || '.yoastBox-focusKeyword';
        },
        getRobotsInstructionsIdentifier: function() {
            return this.config.robotsInstructionsIdentifier || '.yoastBox-robotsInstructions';
        },
        getFocusKeywordIdentifier: function() {
            return this.config.focusKeywordIdentifier || '.yoastBox-focusKeyword .admin__control-text';
        },
        getTitleIdentifier: function() {
            return this.config.titleIdentifier || '.yoastBox-title .admin__control-text';
        },
        getUrlKeyIdentifier: function() {
            return this.config.urlKeyIdentifier || '.yoastBox-urlKey .admin__control-text';
        },
        getMetaTitleIdentifier: function() {
            return this.config.metaTitleIdentifier || '.yoastBox-metaTitle .admin__control-text';
        },
        getMetaDescriptionIdentifier: function() {
            return this.config.metaDescriptionIdentifier || '.yoastBox-metaDescription .admin__control-textarea';
        },
        getMetaKeywordsIdentifier: function() {
            return this.config.metaKeywordsIdentifier || '.yoastBox-metaKeywords';
        },
        getInputElements: function() {
            this.titleInputElement = $(this.getTitleIdentifier()).addClass('yoastBox-inputElement');
            this.urlKeyInputElement = $(this.getUrlKeyIdentifier());
            this.metaTitleInputElement = $(this.getMetaTitleIdentifier());
            this.focusKeywordInputElement = $(this.getFocusKeywordIdentifier()).addClass('yoastBox-inputElement');
            this.metaDescriptionInputElement = $(this.getMetaDescriptionIdentifier());
            this.metaKeywordsInputElement = $(this.getMetaKeywordsIdentifier());

            this.metaTitleChangedCheckbox = this.metaTitleInputElement.siblings('.admin__field-service').find('input[type=checkbox]');
            this.metaDescriptionChangedCheckbox = this.metaDescriptionInputElement.siblings('.admin__field-service').find('input[type=checkbox]');
        },
        getTemplateElements: function() {
            // template input elements
            var template = this.config.contentTemplate,
                matches = template.match(/\{\{([\sa-zA-Z0-9\-_\[\]"=]+)}}/g),
                selector, selectorMatch, inputElement;

            this.templateElements = {};
            $(matches).each(function (ignore, match) {
                if (match !== '{{images}}') {
                    selector = match.replace(/(\{\{|}})/g, '');
                    inputElement = $(selector);
                    if (inputElement.length) {
                        this.templateElements[match] = {
                            type: "inputElement",
                            element: inputElement
                        };
                        if (!inputElement.hasClass('yoastBox-inputElement')) {
                            inputElement.addClass('yoastBox-inputElement');
                        }
                        this.inputElements.push(inputElement);
                    } else {
                        selectorMatch = selector.match("\"([^\"]+)\"");
                        if (selectorMatch[1] && this.sourceData[selectorMatch[1]]) {
                            this.templateElements[match] = {
                                type: "source",
                                element: selectorMatch[1]
                            }
                        }
                    }
                }
            }.bind(this));
        },
        setupSnippetPreview: function() {
            var widget = this;
            widget.snippetPreview = new YoastSEO.SnippetPreview({
                data: {
                    title: widget.getTitleValue(),
                    keyword: widget.getKeywordValue(),
                    metaDesc: widget.getDescriptionValue(),
                    urlPath: widget.getIdentifierValue()
                },
                callbacks: {
                    saveSnippetData: function (data) {
                        widget.saveSnippetData(data);
                    }
                },
                baseUrl: widget.getBaseUrl(),
                targetElement: widget.snippetPreviewElement
            });
        },
        setupApp: function() {
            var widget = this;
            this.app = new YoastSEO.Application({
                snippetPreview: widget.snippetPreview,
                contentAnalysisActive: true,
                targets: {
                    output: 'yoast-seo-output',
                    contentOutput: 'yoast-seo-content-output'
                },
                callbacks: {
                    updateSnippetValues: function() {

                    },
                    saveContentScore: widget.updateContentScore.bind(widget),
                    getData: function() {
                        return {
                            baseUrl: widget.getBaseUrl(),
                            title: widget.getTitleValue(),
                            metaTitle: widget.getTitleValue(),
                            keyword: widget.getKeywordValue(),
                            text: widget.getContentValue(),
                            meta: widget.getDescriptionValue(),
                            metaDesc: widget.getDescriptionValue()
                        };
                    }
                }
            });
            widget.app.seoAssessorPresenter.overall = 'seo-overallScore';
            widget.app.contentAssessorPresenter.overall = 'readability-overallScore';
            widget.app.refresh();
        },
        updateFieldsets: function() {
            $('#yoast-seo-facebook').append(
                $('.yoastBox-facebook-fieldset')
            );
            $('#yoast-seo-twitter').append(
                $('.yoastBox-twitter-fieldset')
            );
            $('#yoast-seo-focus-input-fieldset').append(
                $(this.getFocusKeywordFieldIdentifier())
            );
            $('#yoast-seo-settings-fieldset').append(
                $(this.getRobotsInstructionsIdentifier())
            );
            if (this.config.isKeywordsEnabled) {
                $('#yoast-seo-settings-fieldset').append(
                    this.metaKeywordsInputElement
                );
            }
            $(this.hideInputElements).each(function() {
                var $this = $(this);
                if ($this.hasClass('admin__field')) {
                    $this.hide();
                } else {
                    $(this).parents('.admin__field').hide();
                }
            });
        },
        update: function() {
            $(".seo-focusKeyword").html(this.getKeywordValue());
            this.app.refresh();
            this.snippetPreview.setTitle(this.getTitleValue());
            this.snippetPreview.setUrlPath(this.getIdentifierValue());
            this.snippetPreview.setMetaDescription(this.getDescriptionValue());
        },
        setupEventListeners: function() {
            $(document)
                .on("click", ".fieldset-wrapper-title", this.getTemplateElements.bind(this))
                .on("change", ".yoastBox-inputElement", this.update.bind(this));

            if (this.metaTitleChangedCheckbox.length) {
                this.metaTitleInputElement.on('change', function () {
                    this.metaTitleInputElement.attr('disabled', false);
                    this.metaTitleChangedCheckbox.get(0).checked = false;
                }.bind(this));
            }

            if (this.metaDescriptionChangedCheckbox.length) {
                this.metaDescriptionInputElement.on('change', function () {
                    this.metaDescriptionInputElement.attr('disabled', false);
                    this.metaDescriptionChangedCheckbox.get(0).checked = false;
                }.bind(this))
            }
        },
        saveSnippetData: function(data) {
            this.setTitleValue(data.title);
            this.setDescriptionValue(data.metaDesc);
            this.setIdentifierValue(data.urlPath);
        },
        getBaseUrl: function() {
            var url = window.location.origin;
            if (url.substring(url.length-1, 1) !== '/') {
                url += '/';
            }
            return url;
        },
        getTitleValue: function() {
            var metaTitle = this.metaTitleInputElement.val(),
                pageTitle = this.titleInputElement.val();
            if (metaTitle) {
                return metaTitle;
            } else if (pageTitle) {
                return pageTitle;
            }
            return '';
        },
        setTitleValue: function(value) {
            if (value !== this.metaTitleInputElement.val()) {
                this.metaTitleInputElement.attr('disabled',false).val(value).change();
            }
            return this;
        },
        getIdentifierValue: function() {
            return this.urlKeyInputElement.val();
        },
        setIdentifierValue: function(value) {
            this.urlKeyInputElement.val(value).change();
            return this;
        },
        getKeywordValue: function() {
            return this.focusKeywordInputElement.val();
        },
        setKeywordValue: function(value) {
            this.focusKeywordInputElement.val(value).change();
            return this;
        },
        getContentValue: function() {
            var widget = this,
                content = widget.config.contentTemplate,
                selector, el, value;
            for (selector in widget.templateElements) {
                el = widget.templateElements[selector];
                value = "";
                switch (el.type) {
                    case "inputElement":
                        value = widget.getElementValue(el.element);
                        break;
                    case "source":
                        value = this.sourceData[el.element];
                        break;
                }
                content = content.split(selector).join(value);
            }
            content = content.replace('{{images}}', widget.getEntityImages());

            return content;
        },
        getEntityImages: function () {
            var images = "";
            switch (this.config.entityType) {
                case 'catalog_product':
                    images = this.getProductImages();
                    break;
                default:
                    images = this.config.images.join('<br />');
                    break;
            }
            return images;
        },
        getProductImages: function () {
            var images = [];
            $("[data-role=\"image\"]").each(function (ignore, element) {
                var $el = $(element),
                    src = $el.find("[name*=\"file\"]").val(),
                    alt = $el.find("[name*=\"label\"]").val(),
                    disabled = $el.find("[name*=\"disabled\"]").val();
                if (disabled == '0') {
                    images.push("<img src=\"" + src + "\" alt=\"" + alt + "\" />");
                }
            });
            return images.join("<br /> ");
        },
        getElementValue: function(inputElement) {
            var tag = inputElement.prop('tagName').toLowerCase(),
                value = '';
            switch(tag) {
                case 'input':
                case 'textarea':
                    value = inputElement.val();
                    break;
                case 'select':
                    inputElement.find('option:selected').each(function() {
                        value += (value !== '' ? ', ' : '') + $(this).text();
                    });
                    break;
            }

            return value;
        },
        getDescriptionValue: function() {
            return this.metaDescriptionInputElement.val();
        },
        setDescriptionValue: function(value) {
            if (value !== this.metaDescriptionInputElement.val()) {
                this.metaDescriptionInputElement.attr('disabled',false).val(value).change();
            }
            return this;
        },
        updateContentScore: function (ignore, presenter) {
            presenter.renderOverallRating();
        }
    });

    return $.maxServ.yoastSeo;
});
