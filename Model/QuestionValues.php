<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface;

class QuestionValues extends \Magento\Framework\Model\AbstractModel implements QuestionValuesInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionValues');
    }

    /**
     * Get questionvalues_id
     * @return string
     */
    public function getQuestionvaluesId()
    {
        return $this->getData(self::QUESTIONVALUES_ID);
    }

    /**
     * Set questionvalues_id
     * @param string $questionvaluesId
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     */
    public function setQuestionvaluesId($questionvaluesId)
    {
        return $this->setData(self::QUESTIONVALUES_ID, $questionvaluesId);
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
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     */
    public function setQuestionId($question_id)
    {
        return $this->setData(self::QUESTION_ID, $question_id);
    }

    /**
     * Get Value
     * @return string
     */
    public function getValue()
    {
        return $this->getData(self::VALUE);
    }

    /**
     * Set Value
     * @param string $Value
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     */
    public function setValue($Value)
    {
        return $this->setData(self::VALUE, $Value);
    }
}
