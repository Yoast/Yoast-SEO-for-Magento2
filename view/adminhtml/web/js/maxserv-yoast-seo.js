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
/* global YoastSEO */

define([
    'jquery',
    'domReady!'
], function($) {
    "use strict";

    $.widget('maxServ.yoastSeo', {
        _create: function() {
            var widget = this;
            this.snippetPreviewElement = $('#yoast-seo-snippet-preview')[0];
            this.getInputElements();

            this.inputElements = [
                this.titleInputElement,
                this.urlKeyInputElement,
                this.contentHeadingInputElement,
                this.contentInputElement,
                this.metaTitleInputElement,
                this.focusKeywordInputElement,
                this.metaDescriptionInputElement
            ];

            this.snippetPreview = new YoastSEO.SnippetPreview({
                data : {
                    title: widget.getTitleValue(),
                    keyword: widget.getKeywordValue(),
                    metaDesc: widget.getDescriptionValue(),
                    urlPath: widget.getIdentifierValue()
                },
                callbacks: {
                    saveSnippetData: function(data) {
                        widget.saveSnippetData(data);
                    }
                },
                baseUrl: widget.getBaseUrl(),
                targetElement: this.snippetPreviewElement
            });

            this.app = new YoastSEO.App({
                snippetPreview: this.snippetPreview,
                targets: {
                    output: 'yoast-seo-output'
                },
                callbacks: {
                    updateSnippetValues: function() {
                        console.log('here');
                    },
                    getData: function() {
                        return {
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
            this.app.refresh();
            
            this.setupEventListeners();
        },
        update: function() {
            this.app.refresh();
            this.snippetPreview.setTitle(this.getTitleValue());
            this.snippetPreview.setUrlPath(this.getIdentifierValue());
            this.snippetPreview.setMetaDescription(this.getDescriptionValue());
        },
        setupEventListeners: function() {
            var widget = this;
            $(this.inputElements).each(function() {
                $(this).on('change', function () {
                    widget.update();
                });
            });
        },
        saveSnippetData: function(data) {
            this.setTitleValue(data.title);
            this.setDescriptionValue(data.metaDesc);
            this.setIdentifierValue(data.urlPath);
        },
        getBaseUrl: function() {
            return window.location.protocol + "//" + window.location.hostname + "/";
        },
        getInputElements: function() {
            var body = $('body');
            if (body.hasClass('cms-page-edit')) {
                this.getCmsPageInputElements();
            } else if (body.hasClass('catalog-product-edit')) {
                this.getProductInputElements();
            } else if (body.hasClass('catalog-category-edit')) {
                console.log('get category input elements');
                this.getCategoryInputElements();
            }
        },
        getCmsPageInputElements: function() {
            this.titleInputElement = $('input[type=text][name=title]');
            this.urlKeyInputElement = $('input[type=text][name=identifier]');
            this.contentHeadingInputElement = $('input[type=text][name=content_heading]');
            this.contentInputElement = $('textarea[name=content]');
            this.metaTitleInputElement = $('input[type=text][name=meta_title]');
            this.focusKeywordInputElement = $('input[type=text][name=focus_keyword]');
            this.metaDescriptionInputElement = $('textarea[name=meta_description]');
        },
        getProductInputElements: function() {
            this.titleInputElement = $('input[type=text][name="product[name]"]');
            this.urlKeyInputElement = $('input[type=text][name="product[url_key]"]');
            this.contentHeadingInputElement = false;
            this.contentInputElement = $('textarea[name=description]');
            this.metaTitleInputElement = $('input[type=text][name="product[meta_title]"]');
            this.focusKeywordInputElement = $('input[type=text][name="product[focus_keyword]"]');
            this.metaDescriptionInputElement = $('textarea[name="product[meta_description]"]');
        },
        getCategoryInputElements: function() {
            this.titleInputElement = $('input[type=text][name="name"]');
            this.urlKeyInputElement = $('input[type=text][name="url_key"]');
            this.contentHeadingInputElement = false;
            this.contentInputElement = $('textarea[name=description]');
            this.metaTitleInputElement = $('input[type=text][name="meta_title"]');
            this.focusKeywordInputElement = $('input[type=text][name="focus_keyword"]');
            this.metaDescriptionInputElement = $('textarea[name="meta_description"]');
        },
        getTitleValue: function() {
            var metaTitle = this.metaTitleInputElement.val(),
                pageTitle = this.titleInputElement.val(),
                contentHeading = this.contentHeadingInputElement ? this.contentHeadingInputElement.val() : false;
            if (metaTitle) {
                return metaTitle;
            } else if (pageTitle) {
                return pageTitle;
            } else if (contentHeading) {
                return contentHeading;
            }
            return '';
        },
        setTitleValue: function(value) {
            this.metaTitleInputElement.val(value);
            return this;
        },
        getIdentifierValue: function() {
            return this.urlKeyInputElement.val();
        },
        setIdentifierValue: function(value) {
            this.urlKeyInputElement.val(value);
            return this;
        },
        getKeywordValue: function() {
            return this.focusKeywordInputElement.val();
        },
        setKeywordValue: function(value) {
            this.focusKeywordInputElement.val(value);
            return this;
        },
        getContentValue: function() {
            var contentValue = this.contentInputElement.val();
            if (!contentValue) {
                contentValue = this.metaDescriptionInputElement.val();
            }
            return contentValue;
        },
        setContentValue: function(value) {
            this.metaDescriptionInputElement.val(value);
            return this;
        },
        getDescriptionValue: function() {
            return this.metaDescriptionInputElement.val();
        },
        setDescriptionValue: function(value) {
            this.metaDescriptionInputElement.val(value);
            return this;
        }
    });

    return $.maxServ.yoastSeo;
});
