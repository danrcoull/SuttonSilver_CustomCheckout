/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'uiRegistry',
    'jquery',
    'Magento_Ui/js/form/element/post-code'
], function (_, registry, $,Abstract) {
    'use strict';

    return Abstract.extend({
        defaults: {
            imports: {
                update: '${ $.parentName }.country_id:value'
            },
            countryId : ''
        },

        onUpdate: function (value) {
            console.log(this.countryId);
            var country = registry.get(this.parentName + '.' + 'country_id'),
                validateUrl = 'https://ws.postcoder.com/pcw/[api-key]/codepoint/validatepostcode/[postcode]?format=json',
                autoUrl ='https://ws.postcoder.com/pcw/[api-key]/address/uk/[postcode]?format=json',
                apikey = 'PCWZS-FLZX8-Z9QS9-K2HX6';

            if(this.countryId == 'GB')
            {
                validateUrl = validateUrl.replace('[api-key]',apikey).replace('[postcode]',value);
                autoUrl = validateUrl.replace('[api-key]',apikey).replace('[postcode]',value);
                console.log(validateUrl);

                $.getJSON(validateUrl,function (response) {

                    if(response)
                    {
                        console.log(response);
                        $.getJSON(autoUrl,function (response2) {
                            console.log(response2);
                        });
                    }
                });
            }

        },

        /**
         * @param {String} value
         */
        update: function (value) {
            this.countryId = value;

            var country = registry.get(this.parentName + '.' + 'country_id'),
                options = country.indexedOptions,
                option;

            if (!value) {
                return;
            }

            option = options[value];

            if (option['is_zipcode_optional']) {
                this.error(false);
                this.validation = _.omit(this.validation, 'required-entry');
            } else {
                this.validation['required-entry'] = true;
            }

            this.required(!option['is_zipcode_optional']);
        }
    });
});
