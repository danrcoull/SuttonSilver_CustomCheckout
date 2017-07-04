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
    'use strict';

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
                var fieldset = registry.get(parent);
                ko.utils.arrayForEach(fieldset._elems, function (feature) {

                    if (typeof feature === 'string')
                    {
                        feature = registry.get(feature);
                    }


                    if (typeof feature !== "undefined" && typeof feature !== "string") {

                        if (feature.name == parent + '.street') {
                            if(typeof feature._elems != 'undefined') {
                                feature = registry.get(parent + '.street.0');
                            }
                        }

                        if (feature.inputName != 'country_id' && feature.inputName != 'postcode') {
                            if (hide) {
                                if (typeof feature.hide == 'function') {
                                    feature.hide();
                                }
                            } else {
                                if (typeof feature.show == 'function') {
                                    if(feature.inputName != 'address_choose' && feature.inputName != 'region' ) {
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
