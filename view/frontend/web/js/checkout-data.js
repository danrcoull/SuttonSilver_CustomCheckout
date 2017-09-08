/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true*/
/*global alert*/
/**
 * Checkout adapter for customer data storage
 */
define([
    'jquery',
    'Magento_Customer/js/customer-data'
], function ($, storage) {
    'use strict';

    var cacheKey = 'checkout-data';

    var getData = function () {
        return storage.get(cacheKey)();
    };

    var saveData = function (checkoutData) {
        storage.set(cacheKey, checkoutData);
    };

    if ($.isEmptyObject(getData())) {
        var checkoutData = {
            'homeAddressData': null,
            'personalDetailsData' : null,
            'additionalDetailsData' : null,
        };
        saveData(checkoutData);
    }

    return {
        setHomeAddressData: function (data) {
            var obj = getData();
            obj.homeAddressData = data;
            console.log(obj);
            saveData(obj);
        },
        getHomeAddressData: function () {
            return getData().homeAddressData;
        },
        setPersonalDetailsData: function (data) {
            var obj = getData();
            delete obj.confirm_username;
            obj.personalDetailsData = data;
            saveData(obj);
        },
        getPersonalDetailsData: function () {
            return getData().personalDetailsData;
        },
        setAdditonalDetailsData: function (data) {
            var obj = getData();
            obj.additionalDetailsData = data;
            saveData(obj);
        },
        getAdditonalDetailsData: function () {
            return getData().additionalDetailsData;
        }
    }
});
