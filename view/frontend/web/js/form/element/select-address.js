/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'uiRegistry',
    'jquery',
    'Magento_Ui/js/form/element/select'
], function (ko,_, registry, $,Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            addresses:''
        },
        initialize: function () {
            this._super();
            return this;
        },

        onUpdate: function (value) {
            var addresses = this.addresses,
                address =registry.get(this.parentName + '.' + 'address'),
                city = registry.get(this.parentName + '.' + 'city'),
                region = registry.get(this.parentName + '.' + 'region_id');

            if(typeof addresses[value] !== 'undefined') {
                address.value(addresses[value].number + " " + this.addresses[value].street);
                city.value(addresses[value].posttown);
                region.options().map(function(o) {
                    if(o.title === addresses[value].county)
                    {
                        region.value(o.value);
                    }
                });


            }
        },
        setAddresses: function(addresses)
        {
            this.addresses = addresses;
            var result = [];
            this.addresses.forEach(function($addr,$index){
                //console.log($addr);
                var address = {value:$index, label:$addr.summaryline};
                result.push(address);
            });
            this.setOptions(result)
        }

    });
});
