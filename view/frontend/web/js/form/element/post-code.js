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
            imports: {
                update: '${ $.parentName }.country_id:value'
            }
        },
        initialize:function() {
            this._super();
            //this.toggleLookup(this.value());

        },
        /**
         * @param {String} value
         */
        update: function (value) {

            var country = registry.get(this.parentName + '.' + 'country_id'),
                options = country.indexedOptions,
                option;

            if (!value) {
                return;
            }

            option = options[value];

            if (!('is_zipcode_optional' in option) || option['is_zipcode_optional']) {
                this.error(false);
                this.validation = _.omit(this.validation, 'required-entry');
            } else {
                this.validation['required-entry'] = true;
            }

            this.required(!option['is_zipcode_optional']);

            this.toggleLookup(this.value());
        },
        onUpdate: function (value) {
            var self = this;
            var postcode = value;

            if (this.timeout) {
                clearTimeout(  this.timeout);
            }

            this.timeout = setTimeout(function (value) {
                self.toggleLookup(postcode);
            }, 500);

        },
        toggleLookup:function(value) {

            var self = this,
                parent = self.parentName;


            var country = registry.get(parent + '.country_id'),
                choose_address = registry.get(parent + '.address_choose'),
                validated = false;


            var setShiping = registry.get(self.parentName + '.set_shipping');
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

                    $.ajax({
                        url: '/customcheckout/customer/postcode',
                        type: 'POST',
                        data: {
                            'postcode': value,
                            'isAjax' : true
                        },
                        dataType: 'json',
                        success: function (response2) {
                            choose_address.setAddresses($.parseJSON(response2));
                            choose_address.visible(true);
                        },
                        error: function () {
                            choose_address.setAddresses([]);
                        },
                        beforeSend: setHeader
                    });

                    function setHeader(xhr) {
                        xhr.setRequestHeader('Referer', 'https://cls.suttonsilverdev.co.uk/');
                        xhr.setRequestHeader("X-Requested-With", 'https://cls.suttonsilverdev.co.uk/');
                    }

                } else {
                    choose_address.setAddresses([]);
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
