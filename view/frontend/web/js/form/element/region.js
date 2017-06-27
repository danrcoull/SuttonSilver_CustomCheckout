/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function (_, registry, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false,
            imports: {
                update: '${ $.parentName }.country_id:value'
            }
        },

        /**
         * @param {String} value
         */
        update: function (value) {
            var country = registry.get(this.parentName + '.' + 'country_id');
            if (country) {
                var options = country.indexedOptions,
                    option;

                if (!value) {
                    return;
                }

                option = options[value];

                if (this.skipValidation) {
                    this.validation['required-entry'] = false;
                    this.required(false);
                } else {
                    if (!option['is_region_required']) {
                        this.error(false);
                        this.validation = _.omit(this.validation, 'required-entry');
                    } else {
                        this.validation['required-entry'] = true;
                    }

                    this.required(!!option['is_region_required']);
                }
            }
        },

        /**
         * Filters 'initialOptions' property by 'field' and 'value' passed,
         * calls 'setOptions' passing the result to it
         *
         * @param {*} value
         * @param {String} field
         */
        filter: function (value, field) {

            this._super(value, field);

            var self = this,
                country = ''

            country = registry.get(self.parentName + '.' + 'country_id');

            if (country) {
                var option = country.indexedOptions[value];


                if (option && option['is_region_visible'] === false) {
                    // hide select and corresponding text input field if region must not be shown for selected country
                    self.setVisible(false);

                    if (self.customEntry) {
                        self.toggleInput(false);
                    }
                }
            }
        }
    });
});

