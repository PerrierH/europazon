define([
    'uiComponent',
    'Magento_Customer/js/model/customer'
], function (Component, customer) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Weglio_StepCheckout/check-login'
        },

        /**
         * Check if customer is logged in
         *
         * @return {boolean}
         */
        isLoggedIn: function () {
            return customer.isLoggedIn();
        }
    });
});