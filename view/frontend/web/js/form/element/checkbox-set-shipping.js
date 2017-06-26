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
            this.value(0);
            return this;
        },
        /**
         * @inheritdoc
         */
        onUpdate: function (value) {

            var element = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset');
            var countryId = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.country_id');
            if(typeof element !== 'undefined') {
                error = uiRegistry.get('checkout.steps.shipping-step.shippingAddress.before-fields.select-address-error');
                error.content('As someone must be available to accept delivery, we strongly recommend that your course is sent to your employers address.');
                setTimeout(function() {
                    ko.utils.arrayForEach(element._elems, function (feature) {

                        if (typeof feature === 'string') {
                            feature = uiRegistry.get(feature);
                        }


                        if (typeof feature !== "undefined" && typeof feature !== "string") {

                            if (value == 1) {
                                feature.hide();

                            } else {

                                if (countryId.value() === 'GB') {
                                    if (feature.inputName === 'country_id' || feature.inputName === 'postcode' || feature.inputName === 'address_choose') {
                                        feature.show();
                                    }
                                } else {
                                    feature.show();
                                }

                                if (typeof feature.value == 'function') {
                                    feature.value('');
                                }
                            }
                        }
                    });
                },400);

                if (value == 0) {

                    error.visible(false);
                } else {
                    error.visible(true);

                    var shippinhFieldset = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset';

                    address = checkoutData.getHomeAddressData();
                    console.log(address);
                    Object.keys(address).forEach(function (key) {
                        console.log(key);
                        uiRegistry.get(shippinhFieldset + '.' + key).value(address[key]);
                    });

                }
            }

        }
    })
});
