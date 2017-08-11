<?php


namespace SuttonSilver\CustomCheckout\Model\Attribute\Source;

class Delivery implements \Magento\Framework\Data\CollectionDataSourceInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {

        $array = [
            [
                'value' => 'default_shipping',
                'label' => __('My Work Address'),
	            'checked' => true
            ],
            [
                'value' => 'home_address',
                'label' => __('My Home Address'),
            ]
        ];


        return $array;
    }
}