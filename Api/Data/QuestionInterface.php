<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionInterface
{

    const PRODUCT_IDS = 'product_ids';
    const QUESTION_ID = 'question_id';
    const QUESTION = 'question';
    const QUESTION_TYPE = 'question_type';


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
