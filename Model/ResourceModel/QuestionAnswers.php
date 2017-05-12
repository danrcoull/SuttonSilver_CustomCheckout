<?php


namespace SuttonSilver\CustomCheckout\Model\ResourceModel;

class QuestionAnswers extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('suttonsilver_questionanswers', 'questionanswers_id');
    }
}
