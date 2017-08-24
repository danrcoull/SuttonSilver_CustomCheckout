/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'uiRegistry',
    'jquery',
    'Magento_Ui/js/form/element/country'
], function (ko,_, registry, $,Abstract) {

    return Abstract.extend({

        defaults: {
            timeout:'',
        },
        initialize: function(){
            this._super();
            this.toggleAddress(this.value());
        },
        onUpdate: function (value) {
            if (this.value() != value) {
                this.toggleAddress(value);
            }
        },
        toggleAddress:function (value) {
            if(value === 'GB')
            {
                this.toggleVisibility(true,false)
            }else{
                this.toggleVisibility(false,true)
            }
        },
        toggleVisibility: function (hide,disable) {
            var self = this;
            clearTimeout(self.timeout);
            self.timeout = setTimeout(function() {
                var parent = self.parentName;
                console.log(parent);;

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

                        if (inputName !== 'country_id' && inputName !== 'postcode') {
                            if (hide) {
                                if (typeof feature.hide == 'function') {
                                    feature.hide();
                                }
                            } else {
                                if (typeof feature.show == 'function') {
                                    if (inputName !== 'address_choose' && inputName !== 'region') {
                                        feature.show();
                                    }
                                }
                            }
                        }
                    }
                });

                registry.get(parent+'.address_choose').disabled(disable);



            },400);
        }
    });
});
