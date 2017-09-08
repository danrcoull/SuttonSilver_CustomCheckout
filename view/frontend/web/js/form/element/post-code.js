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
            this.toggleLookup(this.value());

        },
        onUpdate: function (value) {
            this.toggleLookup(value);
        },
        toggleLookup:function(value) {
            var self = this,
                parent = self.parentName;


            var country = registry.get(parent + '.' + 'country_id'),
                choose_address = registry.get(parent + '.' + 'address_choose'),
                validated = false;


            let setShiping = registry.get(self.parentName + '.' + 'set_shipping');
            var show = true;
            if (typeof setShiping !== 'undefined') {
                if (setShiping.value() === 'home_address') {
                    show = false;
                }
            }

            choose_address.visible(false);

            if (country.value() === 'GB' && value !== '' && show) {

                validated = self.postcodeValidation();

                if (validated) {

                    validateUrl = self.validateUrl.replace('[api-key]', self.apikey).replace('[postcode]', value);
                    autoUrl = self.autoUrl.replace('[api-key]', self.apikey).replace('[postcode]', value);

                    $.ajax({
                        url: autoUrl,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (response) {


                                $.ajax({
                                    url: autoUrl,
                                    type: 'GET',
                                    dataType: 'json',
                                    success: function (response2) {
                                        choose_address.setAddresses(response2);
                                        choose_address.visible(true);
                                    },
                                    error: function () {
                                        choose_address.setAddresses([]);
                                    },
                                    beforeSend: setHeader
                                });

                            }

                        },
                        error: function () {
                           validated = false;
                        },
                        beforeSend: setHeader
                    });

                    function setHeader(xhr) {
                        xhr.setRequestHeader('Referer', 'https://cls.suttonsilverdev.co.uk/');
                    }

                }

            } else {
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
