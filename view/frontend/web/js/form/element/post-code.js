/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'uiRegistry',
    'jquery',
    'Magento_Ui/js/form/element/post-code',
    'Magento_Checkout/js/model/postcode-validator',
    'mage/translate',
], function (_, registry, $, Abstract, postcodeValidator, $t) {

    return Abstract.extend({
        defaults: {
            timeout:'',
            validateUrl:'https://ws.postcoder.com/pcw/[api-key]/codepoint/validatepostcode/[postcode]?format=json',
            autoUrl:'https://ws.postcoder.com/pcw/[api-key]/address/uk/[postcode]?format=json',
            apikey: 'PCWZS-FLZX8-Z9QS9-K2HX6'
        },
        initialize:function() {
            this._super();
            if(this.value() !== '') {
                this.toggleLookup();
            }
        },
        onUpdate: function (value) {
            this.toggleLookup();
        },
        toggleLookup:function(){
            var self = this,
                parent = self.parentName;

            setTimeout(function() {
                var country = registry.get(parent + '.' + 'country_id'),
                    choose_address = registry.get(parent + '.' + 'address_choose'),
                    validated = false;

                let value = self.value();

                choose_address.hide();

                if (country.value() === 'GB' && self.value() !== '') {

                    validated = self.postcodeValidation();

                    if (validated) {
                        validateUrl = self.validateUrl.replace('[api-key]', self.apikey).replace('[postcode]', value);
                        autoUrl = self.autoUrl.replace('[api-key]', self.apikey).replace('[postcode]', value);

                        $.getJSON(validateUrl, function (response) {
                            if (response) {

                                $.getJSON(autoUrl, function (response2) {
                                    choose_address.setAddresses(response2);
                                    choose_address.show();
                                }).error(function () {
                                    choose_address.setAddresses([]);
                                });


                            }

                        }).error(function () {
                            validated = false;
                        });
                    }

                } else {
                    validated = self.postcodeValidation();
                }
            },400);
        },
        postcodeValidation: function () {
            var countryId = $('select[name="country_id"]').val(),
                validationResult,
                warnMessage;

            if (this === null || this.value() == null) {
                return true;
            }

            this.warn(null);
            validationResult = postcodeValidator.validate(this.value(), countryId);

            if (!validationResult) {
                warnMessage = $t('Provided Zip/Postal Code seems to be invalid.');

                if (postcodeValidator.validatedPostCodeExample.length) {
                    warnMessage += $t(' Example: ') + postcodeValidator.validatedPostCodeExample.join('; ') + '. ';
                }
                warnMessage += $t('If you believe it is the right one you can ignore this notice.');
                this.warn(warnMessage);
            }

            return validationResult;
        }
    });
});
