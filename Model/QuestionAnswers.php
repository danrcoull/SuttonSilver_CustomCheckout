<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface;

class QuestionAnswers extends \Magento\Framework\Model\AbstractModel implements QuestionAnswersInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionAnswers');
    }

    /**
     * Get questionanswers_id
     * @return string
     */
    public function getQuestionanswersId()
    {
        return $this->getData(self::QUESTIONANSWERS_ID);
    }

    /**
     * Set questionanswers_id
     * @param string $questionanswersId
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     */
    public function setQuestionanswersId($questionanswersId)
    {
        return $this->setData(self::QUESTIONANSWERS_ID, $questionanswersId);
    }

    /**
     * Get customer_id
     * @return string
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customer_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     */
    public function setCustomerId($customer_id)
    {
        return $this->setData(self::CUSTOMER_ID, $customer_id);
    }

    /**
     * Get question_id
     * @return string
     */
    public function getQuestionId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    /**
     * Set question_id
     * @param string $question_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     */
    public function setQuestionId($question_id)
    {
        return $this->setData(self::QUESTION_ID, $question_id);
    }

    public function getValue()
    {
        return $this->getData(self::QUESTION_VALUE);
    }

    public function setValue($value)
    {
        return $this->setData(self::QUESTION_VALUE, $value);
    }


}
