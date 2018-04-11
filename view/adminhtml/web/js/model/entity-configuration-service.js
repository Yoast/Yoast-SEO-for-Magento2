define([
    "jquery",
    "ko"
], function ($, ko) {
    "use strict";

    return {
        configuration: ko.observable(null),
        load: function (namespace) {
            var url = yoastBoxConfig.configUrl + "?namespace=" + namespace;

            $.get(url)
                .done(this.onConfigurationLoad.bind(this))
                .fail(this.onConfigurationFail.bind(this));
        },
        onConfigurationLoad: function (data) {
            this.configuration(data);
        },
        onConfigurationFail: function () {
            this.configuration(null);
            console.log('could not load configuration');
        }
    }
});
