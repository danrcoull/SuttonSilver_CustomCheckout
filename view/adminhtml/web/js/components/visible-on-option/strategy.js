/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define(function () {
    'use strict';

    return {
        defaults: {
            valuesForOptions: ['select','checkbox','radio'],
            imports: {
                toggleVisibility:
                    'suttonsilver_question_form.suttonsilver_question_form.general.question_type:value'
            },
            openOnShow: true,
            isShown: false,
            inverseVisibility: false
        },

        /**
         * Toggle visibility state.
         *
         * @param {Number} selected
         */
        toggleVisibility: function (selected) {
            this.isShown = selected in this.valuesForOptions;
            this.visible(this.inverseVisibility ? !this.isShown : this.isShown);
        }
    };
});
