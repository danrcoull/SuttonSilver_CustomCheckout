/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'jquery',
    'mageUtils',
    'Magento_Ui/js/form/element/checkbox-set',
    'uiRegistry'
], function (_,$, utils, Abstract,uiRegistry) {

    return Abstract.extend({
        defaults: {
            field: 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset'
        },


        /**
         * @inheritdoc
         */
        hasChanged: function () {
            var value = this.value(),
                initial = this.initialValue;
            var element = uiRegistry.get(this.field);
            $.each(element._elems, function(e,uiRegistry){
                console.log(uiRegistry);
                if(value == 0) {
                    uiRegistry.get(uiRegistry).visible(false).disable(false);
                }else{
                    uiRegistry.get(uiRegistry).visible(true).disable(true);
                }
            });
            return this.multiple ?
                !utils.equalArrays(value, initial) :
                this._super();
        }
    });
});
