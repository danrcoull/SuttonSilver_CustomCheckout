/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'jquery',
    'mageUtils',
    'Magento_Ui/js/form/element/checkbox-set',
    'uiRegistry',
    'SuttonSilver_CustomCheckout/js/checkout-data'
], function (ko,_,$, utils, Abstract,uiRegistry,checkoutData) {

    return Abstract.extend({
        defaults: {
            timeout: '',
            field: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset'
        },

        initialize: function () {
            this._super();
            this.initializeError();
        },
        initializeError: function () {
            var error = uiRegistry.get(this.parentName + '.select-address-error');
            error.content('As someone must be available to accept delivery, we strongly recommend that your course is sent to your employers address.');
            error.visible(true);
            //this.toggleErrorVisibility();

        },
        toggleErrorVisibility: function () {

            var error = uiRegistry.get(this.parentName + '.select-address-error');

            if (this.value() === 0) {
                error.visible(false);
            } else {
                error.visible(true);
            }
        },
        onUpdate: function (value) {

            var self = this;

            var shippinhFieldset = self.parentName,
                element = uiRegistry.get(shippinhFieldset),
                countryId = uiRegistry.get(shippinhFieldset + '.country_id'),
                addressChoose = uiRegistry.get(shippinhFieldset + '.address_choose');

            var personalDetails = checkoutData.getPersonalDetailsData();

            console.log(personalDetails);

            uiRegistry.get(shippinhFieldset + '.firstname').value(personalDetails.firstname);
            uiRegistry.get(shippinhFieldset + '.lastname').value(personalDetails.lastname);
            uiRegistry.get(shippinhFieldset + '.telephone').value(personalDetails.daytimeNumber);

            if (value !== 'default_shipping') {

                var address = checkoutData.getHomeAddressData();

                console.log(address);

                Object.keys(address).forEach(function (key) {
                    if (key === 'street') {
                        var key2 = 'street.0';
                    } else {
                        var key2 = key;
                    }
                    var element = uiRegistry.get(shippinhFieldset + '.' + key2);
                    if (typeof element !== 'undefined') {
                        element.value(address[key]);
                    }
                });
            }

            var elements = [
                'company',
                'street.0',
                'city',
                'region_id',
                'region',
                'country_id',
                'postcode',
                'address_choose',
                'dx_number'
            ];

            ko.utils.arrayForEach(elements, function (inputName) {

                var feature = uiRegistry.get(self.parentName + '.' + inputName);

                if (typeof feature !== "undefined") {

                    if (value == 'home_address') {

                        feature.visible(false);

                    } else {

                        var fieldsIn = [
                            'country_id',
                            'postcode',
                            'company',
                            'dx_number',
                        ];

                        var fieldsNotIn = [
                            'region',
                            'region_id',
                            'county',
                            'address_choose'
                        ];

                        if (countryId.value() === 'GB' && addressChoose.value() != '-1') {

                            console.log('gb');
                            if ($.inArray(feature.inputName, fieldsIn) !== -1) {
                                console.log('hide it');
                                feature.visible(true);
                            }else {
                                feature.visible(false);
                            }

                        } else {
                            if ($.inArray(feature.inputName, fieldsNotIn) === -1) {
                                console.log('show it');
                                feature.visible(true);
                            }
                        }
                    }
                }
            });
        }
    });
});
