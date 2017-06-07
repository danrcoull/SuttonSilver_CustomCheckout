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
                'label' => __('Deliver to my work'),
            ],
            [
                'value' => '1',
                'label' => __('Deliver to my home'),
            ]
        ];


        return $array;
    }
}