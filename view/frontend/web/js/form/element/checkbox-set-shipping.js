/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'jquery',
    'mageUtils',
    'Magento_Ui/js/form/element/checkbox-set',
    'uiRegistry'
], function (ko,_,$, utils, Abstract,uiRegistry) {

    return Abstract.extend({
        defaults: {
            field: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset'
        },

        initialize: function () {
            this._super();

            return this;
        },
        /**
         * @inheritdoc
         */
        onUpdate: function () {
            var value = this.value();
            var element = uiRegistry.get(this.field);
            ko.utils.arrayForEach(element._elems, function (feature) {

                if (typeof feature === 'string')
                {
                    feature = uiRegistry.get(feature);
                }

                if (typeof feature !== "undefined" && typeof feature !== "string") {
                    if (value === 1) {
                        feature.hide;
                    } else {
                        feature.show;
                    }
                }
            });

        }
    });
});
