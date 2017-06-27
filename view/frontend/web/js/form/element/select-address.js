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
    'use strict';

    return Abstract.extend({
        defaults: {
            timeout:'',
            addresses:''
        },
        onUpdate: function (value) {
            var addresses = this.addresses,
                address =registry.get(this.parentName + '.' + 'address'),
                city = registry.get(this.parentName + '.' + 'city'),
                region = registry.get(this.parentName + '.' + 'region_id');

            if(value != '-1') {
                this.notAvailable(true);
                if (typeof addresses[value] !== 'undefined') {
                    address.value(addresses[value].number + " " + this.addresses[value].street);
                    city.value(addresses[value].posttown);
                    region.options().map(function (o) {
                        if (o.title === addresses[value].county) {
                            region.value(o.value);
                        }
                    });

                    checkoutData.setHomeAddressData({'home-address':addresses[value]});


                }
            }else{
                this.notAvailable(false);
            }
        },
        notAvailable: function(hide) {
            var self = this;
            setTimeout(function() {
                var parent = self.parentName;

                var fieldset = registry.get(parent);
                ko.utils.arrayForEach(fieldset._elems, function (feature) {

                    if (typeof feature === 'string') {
                        feature = registry.get(feature);
                    }


                    if (typeof feature !== "undefined" && typeof feature !== "string") {

                        if (feature.inputName !== 'country_id' && feature.inputName !== 'postcode' && feature.inputName !== 'address_choose') {
                            if (hide) {
                                if (typeof feature.hide == 'function') {
                                    feature.hide();
                                }
                            } else {
                                if(feature.inputName != 'region' ) {
                                    if (typeof feature.show == 'function') {
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
            console.log('Step 5 - Set Addresses: '+addresses);
            this.addresses = addresses;
            var result = [];
            console.log('Step 6 -Check Length: '+this.addresses.length );
            if(this.addresses.length !== 0) {

                this.addresses.forEach(function ($addr, $index) {
                    console.log('Step 7 -Loop: '+$addr+' '+ $index);
                    var address = {value: $index, label: $addr.summaryline};
                    result.push(address);
                });
            }
            console.log(result);
            var address = {value: '-1', label: 'Enter Address Manually'};
            result.push(address);

            this.setOptions(result)
        }

    });
});
