define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/url-builder',
        'mageUtils',
        'mage/storage',
        'Magento_Checkout/js/model/error-processor',
    ],
    function(ko,quote, customer, urlBuilder, utils, storage, errorProcessor) {
        return {
            getUrlForCustomerCreateUpdate: function (id) {
                var params = (id != '') ? {me: id} : {};
                var urls = {
                    'default': '/customers/:me'
                };
                return this.getUrl(urls, params);
            },
            getUrlForCustomerGetId:function () {
                var params = {};
                var urls = {
                    'default': '/customers/search'
                };
                return this.getUrl(urls, params);
            },
            getCustomer: function () {
                payload = {
                    searchCriteria: {
                        filterGroups: {
                            0: {
                                filters: {
                                    0: {
                                        'field':'email',
                                        'value':'WSH%31%',
                                        'condition_type':'eq'
                                    }
                                }
                            }
                        }
                    }
                };

                return storage.get(
                    this.getUrlForCustomerGetId(),
                    JSON.stringify(payload)
                ).done(
                    function (response) {
                       console.log(response);
                    }
                ).fail(
                    function (response) {
                        errorProcessor.process(response);
                    }
                );
            }
        }
    });