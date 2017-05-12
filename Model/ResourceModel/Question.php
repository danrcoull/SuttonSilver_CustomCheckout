<?php


namespace SuttonSilver\CustomCheckout\Model\ResourceModel;

class Question extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('suttonsilver_question', 'question_id');
    }
}
