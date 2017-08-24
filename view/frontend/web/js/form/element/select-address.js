/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'uiRegistry',
    'jquery',
    'Magento_Ui/js/form/element/select',
    'SuttonSilver_CustomCheckout/js/checkout-data'
], function (ko,_, registry, $,Abstract,checkoutData) {

    return Abstract.extend({
        defaults: {
            timeout:'',
            addresses:''
        },
        onUpdate: function (value) {
            this.setAddress(value);

        },
        setAddress: function(value) {

            var self = this;

            console.log('set-shipping'+self.parentName);
            clearTimeout(self.timeout);
            self.timeout = setTimeout(function () {
                var addresses = self.addresses,
                    address = registry.get(self.parentName + 'street.0'),
                    city = registry.get(self.parentName + '.city'),
                    region = registry.get(self.parentName + '.region_id'),
                    postcode = region.get(self.parentName + '.postcode');

                var addressData = checkoutData.getHomeAddressData();
                console.log(addressData);
                if($.inArray('postcode',addressData)) {
                    if (addressData.country_id == postcode.value()) {
                        value = addressData.address_choose;
                    }
                }

                if (value !== '-1') {

                    if (typeof addresses[value] !== 'undefined') {
                        address.value(addresses[value].number + " " + addresses[value].street);
                        city.value(addresses[value].posttown);
                        region.options().map(function (o) {
                            if (o.title === addresses[value].county) {
                                region.value(o.value);
                            }
                        });
                        var addressData = checkoutData.getHomeAddressData();
                        addressData['home-address'] = addresses[value]
                        checkoutData.setHomeAddressData(addressData);
                    }

                    self.notAvailable(true);

                } else {
                    this.notAvailable(false);
                }
            }, 400);
        },
        notAvailable: function(hide) {
            var self = this;
            setTimeout(function() {
                var parent = self.parentName;
                console.log(parent);
                var elements = [
                    'company',
                    'street',
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

                    var feature = registry.get(parent+'.'+inputName);

                    if(typeof feature != 'undefined') {


                        if (inputName !== 'country_id' && inputName !== 'postcode' && inputName !== 'address_choose') {
                            if (hide) {
                                var fieldsNotIn = [
                                    'dx_number',
                                    'company'
                                ];
                                if($.inArray(inputName,fieldsNotIn) === -1) {
                                    if (typeof feature.hide === 'function') {
                                        feature.hide();
                                    }
                                }
                            } else {

                                var fieldsNotIn = [
                                    'region',
                                    'county'
                                ];

                                if($.inArray(inputName,fieldsNotIn) === -1) {
                                    if (typeof feature.show === 'function') {
                                        feature.show();
                                    }
                                }
                            }

                        }
                    }
                });
            },400);
        },
        setAddresses: function(addresses)
        {
            this.addresses = addresses;
            var result = [];
            if(this.addresses.length !== 0) {

                this.addresses.forEach(function ($addr, $index) {
                    var address = {value: $index, label: $addr.summaryline};
                    result.push(address);
                });
            }
            var address = {value: '-1', label: 'Enter Address Manually'};
            result.push(address);

            this.setOptions(result)
        }

    });
});
