/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global $, $H */

define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            questionProducts = $H(selectedProducts),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        $('in_question_products').value = Object.toJSON(questionProducts);

        /**
         * Register Category Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerCategoryProduct(grid, element, checked) {
            if (checked) {
                if (element.positionElement) {
                    element.positionElement.disabled = false;
                    questionProducts.set(element.value, element.positionElement.value);
                }
            } else {
                if (element.positionElement) {
                    element.positionElement.disabled = true;
                }
                questionProducts.unset(element.value);
            }
            $('in_question_products').value = Object.toJSON(questionProducts);
            grid.reloadParams = {
                'selected_products[]': questionProducts.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function categoryProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Change product position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                questionProducts.set(element.checkboxElement.value, element.value);
                $('in_question_products').value = Object.toJSON(questionProducts);
            }
        }

        /**
         * Initialize category product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function categoryProductRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0],
                position = $(row).getElementsByClassName('input-text')[0];

            if (checkbox && position) {
                checkbox.positionElement = position;
                position.checkboxElement = checkbox;
                position.disabled = !checkbox.checked;
                position.tabIndex = tabIndex++;
                Event.observe(position, 'keyup', positionChange);
            }
        }

        gridJsObject.rowClickCallback = categoryProductRowClick;
        gridJsObject.initRowCallback = categoryProductRowInit;
        gridJsObject.checkboxCheckCallback = registerCategoryProduct;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                categoryProductRowInit(gridJsObject, row);
            });
        }
    };
});
