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
 * @author      Vincent Hornikx <vincent.hornikx@maxser.com>
 * @copyright   Copyright (c) 2016 MaxServ (http://www.maxserv.com)
 * @license     http://opensource.org/licenses/gpl-3.0.en.php General Public License (GPL 3.0)
 *
 */
/* global YoastSEO, window */

define([
    'jquery',
    'domReady!'
], function($) {
    "use strict";

    $.widget('maxServ.yoastSeo', {
        _create: function() {
            this.config = window.yoastBoxConfig;
            if (this.config.hide_yoastbox === true) {
                this.hide();
            } else {
                this.setup();
            }
        },
        hide: function() {
            $('[data-index="search_engine_optimization"]').hide();
        },
        setup: function() {
            var widget = this;
            if (!this.checkInputElements()) {
                setTimeout(function() {
                    widget.setup();
                }, 300);
                return;
            }
            this.element = $('#yoast-seo-wrapper');

            this.snippetPreviewElement = $('#yoast-seo-snippet-preview')[0];
            this.getInputElements();
            $('#yoast-seo-wrapper').tabs({
                active: 0
            });

            this.inputElements = [
                this.titleInputElement,
                this.urlKeyInputElement,
                this.metaTitleInputElement,
                this.focusKeywordInputElement,
                this.metaDescriptionInputElement
            ];

            this.getTemplateElements();
            this.setupSnippetPreview();
            this.setupApp();
            this.updateFieldsets();
            this.setupEventListeners();
        },
        checkInputElements: function() {
            return $('.yoastBox-title .admin__control-text').length &&
            $('.yoastBox-urlKey .admin__control-text').length &&
            $('.yoastBox-metaTitle .admin__control-text').length &&
            $('.yoastBox-metaTitle .admin__control-text').length &&
            $('.yoastBox-metaDescription .admin__control-textarea').length;
        },
        getInputElements: function() {
            this.titleInputElement = $('.yoastBox-title .admin__control-text');
            this.urlKeyInputElement = $('.yoastBox-urlKey .admin__control-text');
            this.metaTitleInputElement = $('.yoastBox-metaTitle .admin__control-text');
            this.focusKeywordInputElement = $('.yoastBox-focusKeyword .admin__control-text');
            this.metaDescriptionInputElement = $('.yoastBox-metaDescription .admin__control-textarea');
        },
        getTemplateElements: function() {
            // template input elements
            var widget = this,
                template = this.config.contentTemplate,
                matches = template.match(/\{\{([\sa-zA-Z0-9\-_\[\]"=]+)}}/g),
                selector, inputElement;

            widget.templateElements = {};
            $(matches).each(function() {
                if (this !== '{{images}}') {
                    selector = this.replace(/(\{\{|}})/g, '');
                    inputElement = $(selector);
                    if (inputElement.length) {
                        widget.templateElements[this] = inputElement;
                        widget.inputElements.push(inputElement);
                    }
                }
            });
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
            this.app = new YoastSEO.App({
                snippetPreview: widget.snippetPreview,
                targets: {
                    output: 'yoast-seo-output'
                },
                callbacks: {
                    updateSnippetValues: function() {

                    },
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
            widget.app.refresh();
        },
        updateFieldsets: function() {
            $('#yoast-seo-facebook').append(
                $('.yoastBox-facebook-fieldset')
            );
            $('#yoast-seo-twitter').append(
                $('.yoastBox-twitter-fieldset')
            );
            $('#yoast-seo-keywords-fieldset').append(
                $('.yoastBox-metaKeywords')
            );
            $('#yoast-seo-focus-input-fieldset').append(
                $('.yoastBox-focusKeyword')
            );
        },
        update: function() {
            this.app.refresh();
            this.snippetPreview.setTitle(this.getTitleValue());
            this.snippetPreview.setUrlPath(this.getIdentifierValue());
            this.snippetPreview.setMetaDescription(this.getDescriptionValue());
        },
        setupEventListeners: function() {
            var widget = this,
                changeFunction = function() {
                    widget.update();
                },
                inputElements = this.inputElements.concat(
                    $('.yoastBox-content textarea')
                );
            $(inputElements).each(function() {
                $(this).on('change', changeFunction);
            });
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
            this.metaTitleInputElement.val(value).change();
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
                selector;
            for (selector in widget.templateElements) {
                content = content.split(selector).join(widget.getElementValue(widget.templateElements[selector]));
            }
            content = content.replace('{{images}}', widget.config.images.join('<br />'));

            return content;
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
            this.metaDescriptionInputElement.val(value).change();
            return this;
        }
    });

    return $.maxServ.yoastSeo;
});
