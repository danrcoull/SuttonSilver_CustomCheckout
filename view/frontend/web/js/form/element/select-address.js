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

            if(typeof value === 'undefined')
            {
                this.notAvailable(true);
                return false;
            }


            var self = this;

            var addresses = self.addresses,
                address = registry.get(self.parentName + '.street.0'),
                city = registry.get(self.parentName + '.city'),
                region = registry.get(self.parentName + '.region_id_input'),
                postcode = registry.get(self.parentName + '.postcode');


            var addressData = checkoutData.getHomeAddressData();
            if(typeof addressData !== 'undefined') {
                if ($.inArray('postcode', addressData)) {
                    if (typeof postcode !== 'undefined') {
                        if (addressData.postcode === postcode.value()) {
                            value = addressData.address_choose;
                        }
                    }
                }
            }



            if (value != '-1') {

                if (typeof addresses[value] !== 'undefined') {

                    //if street doesnt exist use the company instead.
                    var street = "";
                    if (typeof addresses[value].premise !== 'undefined')
                    {
                        street = addresses[value].premise + " ";
                    }else {
                        if (typeof addresses[value].organisation !== 'undefined') {
                            street = addresses[value].organisation + " ";
                        }
                    }

                    address.value(street + addresses[value].street);
                    city.value(addresses[value].posttown);
                    /**region_id.options().map(function (o) {
                        if (o.title === addresses[value].county) {
                            region_id.value(o.value);
                        }
                    });**/

                    $('#'+region.uid).val(addresses[value].county);
                    region.value(addresses[value].county);

                    addressData['region'] = addresses[value].county;
                    addressData['region_id_input'] = addresses[value].county;
                    addressData['home-address'] = addresses[value];
                    addressData['address_choose'] = value;
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

            var elements = [
                'street.0',
                'city',
                'region_id',
                'region',
                'country_id',
                'postcode',
                'address_choose',
                'dx_number',
                'region_id_input',
                'company'
            ];

            ko.utils.arrayForEach(elements, function (inputName) {

                var feature = registry.get(parent + '.' + inputName);
                var country = registry.get(parent + '.country_id');

                if (typeof feature !== 'undefined') {


                    if (inputName !== 'country_id' && inputName !== 'postcode' && inputName !== 'dx_number' && inputName !== 'company') {
                        var fieldsNotIn = [
                            'address_choose'
                        ];

                        console.log("Hide is: " + visible);
                        console.log("Hide for: " + inputName);
                        if (!visible) {

                            feature.visible(false);
                            self.visible(true);

                        } else {

                            console.log($.inArray(inputName, fieldsNotIn));
                            if ($.inArray(inputName, fieldsNotIn) === -1) {
                                feature.visible(true);
                            }
                        }

                    }
                }
            });

            if(visible)
            {
                $('.street .label').show();
            }else{
                $('.street .label').hide();

            }
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
