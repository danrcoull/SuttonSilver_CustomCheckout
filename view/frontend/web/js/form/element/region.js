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

            var country = '',
                self = this;

            setTimeout(function () {
                country = registry.get(self.parentName + '.' + 'country_id');


                if (country) {
                    var options = country.indexedOptions,
                        option;

                    if (!value) {
                        return;
                    }

                    option = options[value];

                    if (self.skipValidation) {
                        self.validation['required-entry'] = false;
                        self.required(false);
                    } else {
                        if (!option['is_region_required']) {
                            self.error(false);
                            self.validation = _.omit(this.validation, 'required-entry');
                        } else {
                            self.validation['required-entry'] = true;
                        }

                        self.required(!!option['is_region_required']);
                    }
                }
            }, 400);
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

            setTimeout(function () {
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
            }, 400);
        }
    });
});

