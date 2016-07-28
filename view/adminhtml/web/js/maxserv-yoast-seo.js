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
            this.pageTitleInputElement = $('input[type=text][name=title]');
            this.pageIdentifierInputElement = $('input[type=text][name=identifier]');
            this.pageContentHeadingInputElement = $('input[type=text][name=content_heading]');
            this.pageContentInputElement = $('textarea[name=content]');
            this.pageMetaTitleInputElement = $('input[type=text][name=meta_title]');
            this.pageMetaKeywordInputElement = $('textarea[name=meta_keywords]');
            this.pageMetaDescriptionInputElement = $('textarea[name=meta_description]');

            this.inputElements = [
                this.pageTitleInputElement,
                this.pageIdentifierInputElement,
                this.pageContentHeadingInputElement,
                this.pageContentInputElement,
                this.pageMetaTitleInputElement,
                this.pageMetaKeywordInputElement,
                this.pageMetaDescriptionInputElement
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
                        console.log(arguments);
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
            return 'http://moetnog.nl/';
        },
        getTitleValue: function() {
            var metaTitle = this.pageMetaTitleInputElement.val(),
                pageTitle = this.pageTitleInputElement.val(),
                contentHeading = this.pageContentHeadingInputElement.val();
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
            this.pageMetaTitleInputElement.val(value);
            return this;
        },
        getIdentifierValue: function() {
            return this.pageIdentifierInputElement.val();
        },
        setIdentifierValue: function(value) {
            this.pageIdentifierInputElement.val(value);
            return this;
        },
        getKeywordValue: function() {
            return this.pageMetaKeywordInputElement.val();
        },
        setKeywordValue: function(value) {
            this.pageMetaKeywordInputElement.val(value);
            return this;
        },
        getContentValue: function() {
            return this.pageContentInputElement.val();
        },
        setContentValue: function(value) {
            this.pageMetaDescriptionInputElement.val(value);
            return this;
        },
        getDescriptionValue: function() {
            return this.pageMetaDescriptionInputElement.val();
        },
        setDescriptionValue: function(value) {
            this.pageMetaDescriptionInputElement.val(value);
            return this;
        }
    });

    return $.maxServ.yoastSeo;
});
