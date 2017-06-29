/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils'
    ],
    function (Component, quote, priceUtils) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'SuttonSilver_CustomCheckout/cart/totals/discount'
            },
            getPureValue: function() {
                console.log(quote.getTotals()());
                var totals =  window.checkoutConfig.totalsData;
                if (totals) {
                    console.log(totals.discount_amount);
                    return totals.discount_amount;
                }
                return 0;
            },
            getValue: function () {
                if(this.getPureValue() !== 0 ) {

                    console.log(this.getPureValue());
                    console.log(quote.getPriceFormat());
                    return priceUtils.formatPrice(this.getPureValue(), quote.getPriceFormat());
                }

                return false;
            }

        });
    }
);
