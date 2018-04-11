define([
    "jquery",
    "ko",
    "uiElement",
    "uiRegistry",
    "MaxServ_YoastSeo/js/model/yoast-data",
    "mage/translate"
], function ($, ko, Component, registry, yoastData) {
    "use strict";

    function getScoreClass (score) {
        var className = 'yoastScore-score yoastScore-score--'
        if (!score) {
            className += 'na';
        } else if (score <= 40) {
            className += 'bad';
        } else if (score <= 70) {
            className += 'ok';
        } else {
            className += 'good';
        }

        return className;
    }

    function getScoreLabel (score) {
        if (!score) {
            return $.mage.__("N/A");
        } else if (score <= 40) {
            return $.mage.__("Bad");
        } else if (score <= 70) {
            return $.mage.__("OK");
        } else {
            return $.mage.__("Good");
        }
    }

    return Component.extend({
        defaults: {
            keyword_score: yoastData.keyword_score,
            keyword_class: ko.pureComputed(function () {
                return getScoreClass(yoastData.keyword_score());
            }),
            keyword_label: ko.pureComputed(function () {
                return getScoreLabel(yoastData.keyword_score())
            }),
            content_score: yoastData.content_score,
            content_class: ko.pureComputed(function () {
                return getScoreClass(yoastData.content_score());
            }),
            content_label: ko.pureComputed(function () {
                return getScoreLabel(yoastData.content_score());
            })
        },
        initialize: function () {
            this._super();

            registry
                .promise('index = yoast_keyword_score')
                .done(function (field) {
                    if (field && field.value) {
                        yoastData.keyword_score(field.value())
                    }
                });

            registry
                .promise('index = yoast_content_score')
                .done(function (field) {
                    if (field.value) {
                        yoastData.content_score(field.value());
                    }
                });
        },

    });
});
