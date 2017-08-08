define(
    [],
    function () {
        'use strict';
        return {
            getRules: function() {
                return {
                    'region_id': {
                        'required':false
                    },
                    'postcode': {
                        'required': false
                    },
                    'country_id': {
                        'required': true
                    }
                };
            }
        };
    }
)