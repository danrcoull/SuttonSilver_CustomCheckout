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
            console.log(this.selected());
            this.initializeError();
            return this;
        },
        initializeError:function(){
            var beforeShippinhFieldset = 'checkout.steps.shipping-step.shippingAddress.before-fields'
            error = uiRegistry.get(beforeShippinhFieldset+'.select-address-error');
            error.content('As someone must be available to accept delivery, we strongly recommend that your course is sent to your employers address.');
            error.visible(true);
            //this.toggleErrorVisibility();

        },
        toggleErrorVisibility:function(){
            var beforeShippinhFieldset = 'checkout.steps.shipping-step.shippingAddress.before-fields'
            error = uiRegistry.get(beforeShippinhFieldset+'.select-address-error');

            if (this.value() == 0) {
                error.visible(false);
            } else {
                error.visible(true);
            }
        },
        onUpdate: function (value) {

            //this.toggleErrorVisibility();

            var self = this;

            var shippinhFieldset = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset',
                element = uiRegistry.get(shippinhFieldset),
                countryId = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id');

            if (typeof element !== 'undefined') {

                setTimeout(function() {


                    ko.utils.arrayForEach(element._elems, function (feature) {

                        if (typeof feature === 'string') {
                            feature = uiRegistry.get(feature);
                        }


                        if (typeof feature !== "undefined" && typeof feature !== "string") {

                            if (feature.name == shippinhFieldset + '.street') {
                                feature = uiRegistry.get(shippinhFieldset + '.street.0');
                            }


                            if (value == 1 && typeof feature.hide == 'function') {

                                feature.hide();

                            } else {

                                var fieldsIn = [
                                    'country_id',
                                    'postcode',
                                    'company',
                                    'dx_number'
                                ]
                                var fieldsNotIn = [
                                    'region',
                                    'county',
                                    'address_choose'
                                ]
                                if (countryId.value() === 'GB') {

                                    if (fieldsIn.indexOf(feature.inputName) != -1) {
                                        if (fieldsNotIn.indexOf(feature.inputName) == -1 && typeof feature.show == 'function') {
                                            feature.show();
                                        }
                                    }
                                } else {
                                    if (fieldsNotIn.indexOf(feature.inputName) == -1 && typeof feature.show == 'function') {
                                        feature.show();
                                    }
                                }
                            }
                        }
                    });
                },400);

                if (value != 0) {

                    address = checkoutData.getHomeAddressData();

                    Object.keys(address).forEach(function (key) {
                        if (key == 'street') {
                            var key2 = 'street.0';
                        } else {
                            key2 = key;
                        }
                        var element = uiRegistry.get(shippinhFieldset + '.' + key2);
                        if (typeof element != 'undefined') {
                            element.value(address[key]);
                        }
                    });
                }
            }
        }
    })
});
