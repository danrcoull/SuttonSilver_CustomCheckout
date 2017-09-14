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
		        'label' => __('County'),
		        'options' => $this->regionCollection->load()->toOptionArray(),
		        'value' => null,
		        'sortOrder' => 1
	        ],
	        'postcode' => [
		        'visible' => false,
		        'formElement' => 'input',
		        'label' => __('Postcode'),
		        'value' => null,
		        'sortOrder' => 2
	        ],
           'country_id' => [

               'component' =>'Magento_Ui/js/form/element/checkbox-set',
               'label' => '',
	           'formElement' => 'checkbox-set',
	           'disabled' => true,
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

	        $fieldSetPointer['country_id']['multiple'] =false;
	        $fieldSetPointer['country_id']['component'] = 'Magento_Ui/js/form/element/checkbox-set';
	        $fieldSetPointer['country_id']['disabled'] = false;
	        $fieldSetPointer['country_id']['value'] = 'GB';
	        $fieldSetPointer['country_id']['default'] = 'GB';
	        $fieldSetPointer['country_id']['initialValue'] = 'GB';
	        $fieldSetPointer['country_id']['config']['template'] = 'ui/form/element/checkbox-set';
	        $fieldSetPointer['country_id']['options'] = [
		        ['value' => 'GB','label' => __('UK Mainland')],
		        ['value' => 'US','label' => __('Overseas')]
	        ];

	       // die(var_dump($fieldSetPointer['country_id']));
        }

	    unset($jsLayout['components']['block-summary']['children']['block-shipping']['children']['address-fieldsets']['children']['region']);
        unset($jsLayout['components']['block-summary']['children']['block-shipping']['children']['address-fieldsets']['children']['region_id']);
        return $jsLayout;
    }
}
