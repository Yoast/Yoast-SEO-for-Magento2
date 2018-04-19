define([
    "ko"
], function (ko) {
    "use strict";

    return {
        promise: function (field) {
            return new Promise(function (resolve, reject) {
                if (field && field.hasOwnProperty("value") && ko.isObservable(field.value)) {
                    resolve(field.value());
                } else {
                    resolve('');
                }
            });
        }
    };
});
