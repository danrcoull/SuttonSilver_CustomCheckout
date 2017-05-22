<?php

namespace SuttonSilver\CustomCheckout\Model\Config\Source;
use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(
            ['label' => 'Text Field',  'value' => 'text'],
            ['label' => 'Selectbox', 'value' => 'select'],
            ['label' => 'Checkbox', 'value' => 'checkbox' ],
            ['label' => 'Radio Buttons', 'value' => 'radio'],
        );

        return $options;
    }
}