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
    'mage/translate'
], function (_, registry, $, Abstract, postcodeValidator, $t) {
    'use strict';

    return Abstract.extend({

        onUpdate: function (value) {

            var parent = this.parentName;
            var postcode = this;
            var country = registry.get(this.parentName + '.' + 'country_id'),
                choose_address = registry.get(this.parentName + '.' + 'address_choose'),
                validateUrl = 'https://ws.postcoder.com/pcw/[api-key]/codepoint/validatepostcode/[postcode]?format=json',
                autoUrl ='https://ws.postcoder.com/pcw/[api-key]/address/uk/[postcode]?format=json',
                apikey = 'PCWZS-FLZX8-Z9QS9-K2HX6';


            if(country.value() === 'GB')
            {
                this.postcodeValidation();

                validateUrl = validateUrl.replace('[api-key]',apikey).replace('[postcode]',value);
                autoUrl = autoUrl.replace('[api-key]',apikey).replace('[postcode]',value);


                setTimeout(function() {
                    $.getJSON(validateUrl, function (response) {
                        if (response) {
                            choose_address.show();
                            $.getJSON(autoUrl, function (response2) {
                                choose_address.setAddresses(response2);
                            });
                        }else{
                            choose_address.hide();
                            choose_address.setAddresses('[]');
                        }
                    });
                } ,2000);

            }else{
                choose_address.hide();
            }

        },
        postcodeValidation: function () {
            var countryId = $('select[name="country_id"]').val(),
                validationResult,
                warnMessage;

            if (this == null || this.value() == null) {
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
