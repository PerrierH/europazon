define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Weglio_StepCheckout/check-login'
            },

            //add here your logic to display step,
            isVisible: ko.observable(true),

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                stepNavigator.registerStep(
                    'login',
                    null,
                    'Connexion',
                    this.isVisible,
                    _.bind(this.navigate, this),
                    9
                );

                return this;
            },

            navigate: function () {

                this.isVisible(true);
            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                stepNavigator.next();
            }
        });
    },

function (Component, customer) {
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
