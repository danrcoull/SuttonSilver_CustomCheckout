<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionAnswersInterface
{

    const QUESTION_ID = 'question_id';
    const CUSTOMER_ID = 'customer_id';
    const QUESTIONANSWERS_ID = 'questionanswers_id';


    /**
     * Get questionanswers_id
     * @return string|null
     */
    
    public function getQuestionanswersId();

    /**
     * Set questionanswers_id
     * @param string $questionanswers_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     */
    
    public function setQuestionanswersId($questionanswersId);

    /**
     * Get customer_id
     * @return string|null
     */
    
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customer_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     */
    
    public function setCustomerId($customer_id);

    /**
     * Get question_id
     * @return string|null
     */
    
    public function getQuestionId();

    /**
     * Set question_id
     * @param string $question_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     */
    
    public function setQuestionId($question_id);
}
