define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/url-builder',
        'mageUtils',
        'mage/storage'
    ],
    function(ko,quote, customer, urlBuilder, utilsstorage) {
        return {
            getUrlForCustomerCreate: function (customer) {
                var params = (this.getCheckoutMethod() == 'guest') ? {cartId: quote.getQuoteId()} : {};
                var urls = {
                    'guest': '/guest-carts/:cartId/totals-information',
                    'customer': '/carts/mine/totals-information'
                };
                return this.getUrl(urls, params);
            },
        }
    });