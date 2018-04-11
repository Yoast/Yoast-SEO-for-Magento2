define([
    "jquery",
    "ko"
], function ($, ko) {
    "use strict";

    return {
        configuration: ko.observable(null),
        load: function (dataSource) {
            var url = yoastBoxConfig.configUrl + "?dataSource=";

            if (dataSource.indexOf(".") > -1) {
                dataSource = dataSource.substring(0, dataSource.indexOf("."));
            }
            url += dataSource;

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
