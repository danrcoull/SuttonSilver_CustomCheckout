/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'ko',
        'Magento_Checkout/js/model/totals',
        'uiComponent',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/model/quote'
    ],
    function (ko, totals, Component, stepNavigator, quote) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'SuttonSilver_CustomCheckout/summary/cart-items'
            },
            totals: totals.totals(),
            getItems: totals.getItems(),
            getItemsQty: function() {
                return parseFloat(this.totals.items_qty);
            },
            isItemsBlockExpanded: function () {
                return quote.isVirtual() || stepNavigator.isProcessed('personal-details');
            }
        });
    }
);
