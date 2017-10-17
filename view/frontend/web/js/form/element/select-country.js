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
            this.toggleAddress('GB');

        },
        onUpdate: function (value) {
            this.toggleAddress(value);
        },
        toggleAddress:function (value) {
            var feature = registry.get(this.parentName + '.address_choose');
            var enterManual = false;

            if(typeof feature !== 'undefined')
            {
                enterManual = (feature.value() != '-1');
            }

            if(value === 'GB' && !enterManual)
            {
                this.toggleVisibility(true)
            }else{
                this.toggleVisibility(false)
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

            ko.utils.arrayForEach(elements, function (inputName) {

                var feature = registry.get(parent + '.' + inputName);

                if (typeof feature !== 'undefined') {


                    var fieldsNotIn = [
                        'region',
                        'region_id',
                        'county',
                        'address_choose',
                        'region_id_input'
                    ];

                    if (inputName !== 'country_id' && inputName !== 'postcode' && inputName !== 'dx_number' && inputName !== 'company') {
                        if (hide) {
                            $('.street .label').hide();
                            feature.visible(false);

                        } else {
                            $('.street .label').show();
                            if ($.inArray(feature.inputName, fieldsNotIn) === -1) {
                                feature.visible(true);
                            }
                        }

                        feature.value('');

                    }
                }


            });

        }
    });
});
