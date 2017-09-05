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
        'Magento_Checkout/js/model/step-navigator',
        'uiRegistry'
    ],
    function(ko,$, quote, customer, checkoutData, utils, storage, errorProcessor,stepNavigator, uiRegistry) {
        return {
            getUrlForCustomerCreateUpdate: function () {
                var params = {};
                var urls = {
                    'default': '/customcheckout/customer/create'
                };
                return urls.default;
            },
            createCustomer: function () {


                var self = this,
                    step = uiRegistry.get('checkout.steps.my-new-step');

                var payload = JSON.stringify($('#custom-checkout-form').serializeArray());

                var config = {};

                console.log(step);
                step.isLoading(true);

                $.ajax({
                    url: self.getUrlForCustomerCreateUpdate(),
                    type: 'POST',
                    data: {
                        'data': payload
                    },

                }).done(
                    function (response) {
                        console.log(response);
                        if(response.success) {
                            step.isLoading(false);

                            window.isCustomerLoggedIn = true;
                            window.checkoutConfig = response.checkoutConfig;
                            window.customerData = window.checkoutConfig.customerData;

                            stepNavigator.next();
                        }
                    }
                ).fail(
                    function (response) {
                        step.isLoading(false);
                        console.log(response);
                    }
                );

            }
        }
    });