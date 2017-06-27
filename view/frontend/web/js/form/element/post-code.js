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

        onUpdate: function (value) {
            var self = this,
                parent = self.parentName;

            var country = registry.get(parent + '.' + 'country_id'),
                choose_address = registry.get(parent + '.' + 'address_choose'),
                validated = false;

            choose_address.hide();

            if(country.value() === 'GB' && value !== '') {


                validated = self.postcodeValidation();

                if (validated) {
                    console.log(value);
                    validateUrl = self.validateUrl.replace('[api-key]', self.apikey).replace('[postcode]', value);
                    autoUrl = self.autoUrl.replace('[api-key]', self.apikey).replace('[postcode]', value);

                    console.log('Step 1 - Validate: ' + validateUrl);
                    console.log('Step 1 - Validate: ' + autoUrl);

                    $.getJSON(validateUrl, function (response) {
                        console.log('Step 3 - Postcode Exists Exists: ' + response);
                        if (response) {

                            $.getJSON(autoUrl, function (response2) {
                                console.log('Step 3 - Validated - Get Addresses: ' + response2);
                                choose_address.setAddresses(response2);
                            }).error(function () {
                                console.log('Step 3 - Validated - Failed: ');
                                choose_address.setAddresses([]);
                            });

                            console.log('Step 4 - Show: ');
                            choose_address.show();
                        }

                    }).error(function () {
                        validated =false;
                    });
                }

            }else {
                validated = self.postcodeValidation();
            }

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
