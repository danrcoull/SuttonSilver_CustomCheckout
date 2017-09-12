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
            getUrlForCustomerLogout: function () {
                var params = {};
                var urls = {
                    'default': '/customcheckout/customer/logout'
                };
                return urls.default;
            },
            logoutCustomer: function() {
                var self = this;
                $.ajax({
                    url: self.getUrlForCustomerLogout(),
                    type: 'POST',
                }).done(
                    function (response) {
                        console.log('response success');
                        window.isCustomerLoggedIn = false;
                    }
                );
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
                        if(response.success) {
                            step.isLoading(false);

                            window.isCustomerLoggedIn = true;
                            window.checkoutConfig = response.checkoutConfig;
                            window.customerData = window.checkoutConfig.customerData;

                            uiRegistry.get('checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.set_shipping').setHidden();

                            stepNavigator.next();
                        }
                    }
                ).fail(
                    function (response) {
                        step.isLoading(false);
                    }
                );

            }
        }
    });