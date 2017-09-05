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
        initialize: function(){
            this._super();
            this.visible(false);
            return this;

        },
        onUpdate: function (value) {

            this.setAddress(value);

        },
        setAddress: function(value) {

            var self = this;

            var addresses = self.addresses,
                address = registry.get(self.parentName + '.street.0'),
                city = registry.get(self.parentName + '.city'),
                region = registry.get(self.parentName + '.region_id'),
                postcode = registry.get(self.parentName + '.postcode');


            var addressData = checkoutData.getHomeAddressData();
            console.log(value);
            if ($.inArray('postcode', addressData)) {
                if(typeof postcode !== 'undefined') {
                    if (addressData.postcode === postcode.value()) {
                        value = addressData.address_choose;
                    }
                }
            }

            if (value != '-1') {

                if (typeof addresses[value] !== 'undefined') {
                    console.log(addresses[value]);
                    address.value(addresses[value].number + " " + addresses[value].street);
                    city.value(addresses[value].posttown);
                    region.options().map(function (o) {
                        if (o.title === addresses[value].county) {
                            region.value(o.value);
                        }
                    });
                    var addressData = checkoutData.getHomeAddressData();
                    addressData['home-address'] = addresses[value];
                    checkoutData.setHomeAddressData(addressData);
                }

                self.notAvailable(false);

            } else {
                self.notAvailable(true);
            }

        },
        notAvailable: function(visible) {
            var self = this;
            var parent = self.parentName;

            console.log(parent);
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

                var feature = registry.get(parent + '.' + inputName);
                var country = registry.get(parent + '.country_id');

                if (typeof feature !== 'undefined') {


                    if (inputName !== 'country_id' && inputName !== 'postcode' && inputName !== 'dx_number' && inputName !== 'company') {
                        if (visible) {
                            var fieldsNotIn = [
                                'region',
                                'region_id',
                                'county',
                                'address_choose'
                            ];
                        } else {
                            var fieldsNotIn = [
                                'dx_number',
                                'company',
                                'address_choose'
                            ];

                        }

                        if ($.inArray(inputName, fieldsNotIn) === -1) {
                            feature.visible(visible);
                        }

                    }
                }
            });
        },
        setAddresses: function(addresses)
        {
            console.log(addresses);
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
