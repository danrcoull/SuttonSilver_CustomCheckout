define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'SuttonSilver_CustomCheckout/js/checkout-data',
        'mageUtils',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/step-navigator'
    ],
    function(ko,$, quote, customer, checkoutData, utils, storage, errorProcessor,stepNavigator) {
        return {
            getUrlForCustomerCreateUpdate: function () {
                var params = {};
                var urls = {
                    'default': '/customcheckout/customer/create'
                };
                return urls.default;
            },
            createCustomer: function () {
                var self = this;
                var payload = $('#custom-checkout-form').serializeArray();
                var config = {};
                $.map( payload, function( n, i ) {
                    config[n.name] = n.value;
                });
                //console.log(config);
                $.ajax({
                    url: self.getUrlForCustomerCreateUpdate(),
                    type: 'POST',
                    data: {
                        'data': JSON.stringify(config)
                    },

                }).done(
                    function (response) {
                        console.log(response);
                        stepNavigator.next();
                    }
                ).fail(
                    function (response) {
                        console.log(response);
                    }
                );

            }
        }
    });