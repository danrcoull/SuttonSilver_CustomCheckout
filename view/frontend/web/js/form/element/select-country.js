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
            timeout:''
        },
        initialize: function(){
            this._super();
            this.toggleAddress('GB', true);

        },
        onUpdate: function (value) {
            this.toggleAddress(value);
        },
        toggleAddress:function (value) {
            console.log("Country Changed to: "+value);
            var address_choose = registry.get(this.parentName + '.address_choose');

            if(typeof address_choose !== 'undefined') {
                if (value === 'GB') {

                    address_choose.notAvailable(false);
                } else {
                    address_choose.notAvailable(true);

                }
            }
        },
        toggleVisibility: function (hide) {
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

            //loop over all elements
            ko.utils.arrayForEach(elements, function (inputName) {

                //get the feature
                var feature = registry.get(parent + '.' + inputName);

                //check if the feature exists
                if (typeof feature !== 'undefined') {


                    var fieldsNotIn = [
                        'region',
                        'region_id',
                        'county',
                        'address_choose',
                        'region_id_input'
                    ];

                    if (inputName !== 'country_id' && inputName !== 'postcode' && inputName !== 'dx_number' && inputName !== 'company') {

                        console.log("Hide is: "+hide);
                        console.log("Hide for: "+inputName);
                        if (hide) {

                            feature.visible(false);

                        } else {

                            console.log($.inArray(inputName, fieldsNotIn));
                            if ($.inArray(inputName, fieldsNotIn) === -1) {
                                feature.visible(true);
                            }
                        }

                        feature.value('');

                    }
                }


            });

            //hide the abigous label
            if(hide)
            {
                $('.street .label').hide();
            }else{
                $('.street .label').show();
            }

        }
    });
});
