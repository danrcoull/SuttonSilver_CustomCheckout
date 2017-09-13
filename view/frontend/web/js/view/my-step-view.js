define(
    [
        'jquery',
        'underscore',
        'Magento_Ui/js/form/form',
        'ko',
        'Magento_Checkout/js/model/step-navigator',
        'SuttonSilver_CustomCheckout/js/checkout-data',
        'SuttonSilver_CustomCheckout/js/model/customer-create',
        'uiRegistry',
        'Magento_Checkout/js/model/quote',
        'mage/translate'
    ],
    function (
        $,
        _,
        Component,
        ko,
        stepNavigator,
        checkoutData,
        customerCreate,
        registry,
        quote,
        $t
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
            isLoading: ko.observable(false),
            quoteIsVirtual: quote.isVirtual(),
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

                registry.async('checkoutProvider')(function (checkoutProvider) {

                    var personalDetailsData = checkoutData.getPersonalDetailsData();

                    if (personalDetailsData) {
                        checkoutProvider.set(
                            'personalDetails',
                            $.extend({}, checkoutProvider.get('personalDetails'), personalDetailsData)
                        );
                    }
                    checkoutProvider.on('personalDetails', function (personalDetailsData) {
                        checkoutData.setPersonalDetailsData(personalDetailsData);
                    });

                    var homeAddressData = checkoutData.getHomeAddressData();

                    if (homeAddressData) {
                        checkoutProvider.set(
                            'homeAddress',
                            $.extend({}, checkoutProvider.get('homeAddress'), homeAddressData)
                        );
                    }
                    checkoutProvider.on('homeAddress', function (homeAddressData) {
                        checkoutData.setHomeAddressData(homeAddressData);
                    });


                    var additonalDetailsData = checkoutData.getAdditonalDetailsData();

                    if (additonalDetailsData) {
                        checkoutProvider.set(
                            'additionalDetails',
                            $.extend({}, checkoutProvider.get('additionalDetails'), additonalDetailsData)
                        );
                    }
                    checkoutProvider.on('additionalDetails', function (additonalDetailsData) {
                        checkoutData.setAdditonalDetailsData(additonalDetailsData);
                    });
                });

                /**var shippignStep = registry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.set_shipping');
                if(typeof shippignStep !=='undefined')
                {
                    shippignStep.setHidden();
                }**/

                return this;
            },

            /**
             * The navigate() method is responsible for navigation between checkout step
             * during checkout. You can add custom logic, for example some conditions
             * for switching to your custom step
             */
            navigate: function () {

            },
            validate: function() {
                // trigger form validation
                this.source.set('params.invalid', false);
                this.source.trigger('personalDetails.data.validate');
                this.source.trigger('homeAddress.data.validate');
                this.source.trigger('additionalDetails.data.validate');

                // verify that form data is valid
                if (!this.source.get('params.invalid')) {
                    var response = customerCreate.createCustomer();
                }
            },
            getFormKey: function() {
                return window.checkoutConfig.formKey;
            }
        });
    });