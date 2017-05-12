<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\Data\QuestionInterface;

class Question extends \Magento\Framework\Model\AbstractModel implements QuestionInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SuttonSilver\CustomCheckout\Model\ResourceModel\Question');
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
     * @param string $questionId
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestionId($questionId)
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * Get qutions_id
     * @return string
     */
    public function getQutionsId()
    {
        return $this->getData(self::QUTIONS_ID);
    }

    /**
     * Set qutions_id
     * @param string $qutions_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQutionsId($qutions_id)
    {
        return $this->setData(self::QUTIONS_ID, $qutions_id);
    }

    /**
     * Get question
     * @return string
     */
    public function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }

    /**
     * Set question
     * @param string $question
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestion($question)
    {
        return $this->setData(self::QUESTION, $question);
    }

    /**
     * Get question_type
     * @return string
     */
    public function getQuestionType()
    {
        return $this->getData(self::QUESTION_TYPE);
    }

    /**
     * Set question_type
     * @param string $question_type
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestionType($question_type)
    {
        return $this->setData(self::QUESTION_TYPE, $question_type);
    }

    /**
     * Get question_answer
     * @return string
     */
    public function getQuestionAnswer()
    {
        return $this->getData(self::QUESTION_ANSWER);
    }

    /**
     * Set question_answer
     * @param string $question_answer
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestionAnswer($question_answer)
    {
        return $this->setData(self::QUESTION_ANSWER, $question_answer);
    }

    /**
     * Get product_ids
     * @return string
     */
    public function getProductIds()
    {
        return $this->getData(self::PRODUCT_IDS);
    }

    /**
     * Set product_ids
     * @param string $product_ids
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setProductIds($product_ids)
    {
        return $this->setData(self::PRODUCT_IDS, $product_ids);
    }
}
