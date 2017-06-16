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
            ['label' => 'Text Area',  'value' => 'textarea'],
            ['label' => 'Selectbox', 'value' => 'select'],
            ['label' => 'Checkbox', 'value' => 'checkbox' ],
            ['label' => 'Radio Buttons', 'value' => 'radio'],
            ['label' => 'Yes/No', 'value' => 'yes-no'],
        );

        return $options;
    }
}