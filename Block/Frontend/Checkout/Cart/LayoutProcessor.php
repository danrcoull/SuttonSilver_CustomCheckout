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

            'country_id' => [
                'visible' => true,
                'formElement' => 'select',
                'label' => __('Country'),
                'options' => $this->countryCollection->loadByStore()->toOptionArray(),
                'value' => null
            ],
	        'region_id' => [
		        'visible' => true,
		        'formElement' => 'select',
		        'label' => __('State/Province'),
		        'options' => $this->regionCollection->load()->toOptionArray(),
		        'value' => null,
		        'sortOrder' => 1
	        ],
	        'postcode' => [
		        'visible' => true,
		        'formElement' => 'input',
		        'label' => __('Zip/Postal Code'),
		        'value' => null,
		        'sortOrder' => 2
	        ],
           /**'country_id' => [
               'options' => [
                   ['value' => 'GB','label' => __('UK Mainland')],
                   ['value' => 'null','label' => __('Overseas')]
               ],
               'label' => '',
               'formElement' => 'checkbox-set',
               'visible' => true,
               'sortOrder' => 3
           ]**/
        ];


        if (isset($jsLayout['components']['block-summary']['children']['block-shipping']['children']
            ['address-fieldsets']['children'])
        ) {
            $fieldSetPointer = &$jsLayout['components']['block-summary']['children']['block-shipping']
            ['children']['address-fieldsets']['children'];
            $fieldSetPointer = $this->merger->merge($elements, 'checkoutProvider', 'shippingAddress', $fieldSetPointer);
            $fieldSetPointer['region_id']['config']['skipValidation'] = true;
        }
        return $jsLayout;
    }
}
