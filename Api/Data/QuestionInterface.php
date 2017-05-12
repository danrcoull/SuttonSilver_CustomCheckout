<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionInterface
{

    const QUESTION_ID = 'question_id';
    const QUESTION_ANSWER = 'question_answer';
    const QUESTION = 'question';
    const PRODUCT_IDS = 'product_ids';
    const QUESTION_TYPE = 'question_type';
    const QUTIONS_ID = 'qutions_id';


    /**
     * Get question_id
     * @return string|null
     */
    
    public function getQuestionId();

    /**
     * Set question_id
     * @param string $question_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQuestionId($questionId);

    /**
     * Get qutions_id
     * @return string|null
     */
    
    public function getQutionsId();

    /**
     * Set qutions_id
     * @param string $qutions_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQutionsId($qutions_id);

    /**
     * Get question
     * @return string|null
     */
    
    public function getQuestion();

    /**
     * Set question
     * @param string $question
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQuestion($question);

    /**
     * Get question_type
     * @return string|null
     */
    
    public function getQuestionType();

    /**
     * Set question_type
     * @param string $question_type
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQuestionType($question_type);

    /**
     * Get question_answer
     * @return string|null
     */
    
    public function getQuestionAnswer();

    /**
     * Set question_answer
     * @param string $question_answer
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQuestionAnswer($question_answer);

    /**
     * Get product_ids
     * @return string|null
     */
    
    public function getProductIds();

    /**
     * Set product_ids
     * @param string $product_ids
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setProductIds($product_ids);
}
