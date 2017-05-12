<?php


namespace SuttonSilver\CustomCheckout\Model\ResourceModel\Question;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'SuttonSilver\CustomCheckout\Model\Question',
            'SuttonSilver\CustomCheckout\Model\ResourceModel\Question'
        );
    }
}
