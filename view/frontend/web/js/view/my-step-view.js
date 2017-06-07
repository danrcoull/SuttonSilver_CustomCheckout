define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function (
        $,
        _,
        Component,
        ko,
        stepNavigator
    ) {
        'use strict';
        /**
         *
         * mystep - is the name of the component's .html template,
         * <Vendor>_<Module>  - is the name of the your module directory.
         *
         */
        return Component.extend({
            defaults: {
                template: 'SuttonSilver_CustomCheckout/mystep'
            },

            //add here your logic to display step,
            isVisible: ko.observable(true),

            /**
             *
             * @returns {*}
             */
            initialize: function () {
                this._super();
                // register your step
                stepNavigator.registerStep(
                    'personal-details',
                    null,
                    'Personal Details',
                    this.isVisible,
                    _.bind(this.navigate, this),
                    0
                );

                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {

            },

            /**
             * @returns void
             */
            navigateToNextStep: function () {
                    stepNavigator.next();
            },
            validatePersonalDetails: function() {
               // trigger form validation
                this.source.set('params.invalid', false);
                this.source.trigger('personalDetails.data.validate');

                console.log(this);
                // verify that form data is valid
                if (!this.source.get('params.invalid')) {
                    // data is retrieved from data provider by value of the customScope property
                    var formData = this.source.get('personalDetails');
                    this.navigateToNextStep();
                }
            }
        });
    }
);