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

        onUpdate: function (value) {

            var country = registry.get(this.parentName + '.' + 'country_id'),
                choose_address = registry.get(this.parentName + '.' + 'address_choose'),
                validateUrl = 'https://ws.postcoder.com/pcw/[api-key]/codepoint/validatepostcode/[postcode]?format=json',
                autoUrl ='https://ws.postcoder.com/pcw/[api-key]/address/uk/[postcode]?format=json',
                apikey = 'PCWZS-FLZX8-Z9QS9-K2HX6';

            if(country.value() == 'GB')
            {
                validateUrl = validateUrl.replace('[api-key]',apikey).replace('[postcode]',value);
                autoUrl = autoUrl.replace('[api-key]',apikey).replace('[postcode]',value);
                setTimeout(function() {
                    $.getJSON(validateUrl, function (response) {
                        if (response) {
                            $.getJSON(autoUrl, function (response2) {
                                choose_address.setAddresses(response2);
                            });
                        }
                    });
                } ,200);
            }

        },
    });
});
