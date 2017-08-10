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
                'value' => '0',
                'label' => __('My Work Address'),
	            'checked' => true
            ],
            [
                'value' => '1',
                'label' => __('My Home Address'),
            ]
        ];


        return $array;
    }
}