<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SuttonSilver\CustomCheckout\Block\Frontend\Checkout\Cart;

use Magento\TestFramework\Event\Magento;

class LayoutProcessor extends \Magento\Checkout\Block\Cart\LayoutProcessor
{

   public function process($jsLayout)
    {
        $elements = [

	        'region_id' => [
		        'visible' => false,
		        'formElement' => 'select',
		        'label' => __('State/Province'),
		        'options' => $this->regionCollection->load()->toOptionArray(),
		        'value' => null,
		        'sortOrder' => 1
	        ],
	        'postcode' => [
		        'visible' => false,
		        'formElement' => 'input',
		        'label' => __('Zip/Postal Code'),
		        'value' => null,
		        'sortOrder' => 2
	        ],
           'country_id' => [
               'options' => [
                   ['value' => 'GB','label' => __('UK Mainland')],
                   ['value' => 'null','label' => __('Overseas')]
               ],
               'label' => '',
               'formElement' => 'checkbox-set',
               'visible' => true,
               'sortOrder' =>1
           ]
        ];


        if (isset($jsLayout['components']['block-summary']['children']['block-shipping']['children']
            ['address-fieldsets']['children'])
        ) {
            $fieldSetPointer = &$jsLayout['components']['block-summary']['children']['block-shipping']
            ['children']['address-fieldsets']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'shippingAddress', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;
        }

	    unset($jsLayout['components']['block-summary']['children']['block-shipping']['children']['address-fieldsets']['children']['region']);
        unset($jsLayout['components']['block-summary']['children']['block-shipping']['children']['address-fieldsets']['children']['region_id']);
        return $jsLayout;
    }
}
