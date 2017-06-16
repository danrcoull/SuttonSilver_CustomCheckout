<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionValuesInterface
{

    const QUESTION_ID = 'question_id';
    const QUESTIONVALUES_ID = 'questionvalues_id';
    const VALUE = 'question_value';


    /**
     * Get questionvalues_id
     * @return string|null
     */
    
    public function getQuestionvaluesId();

    /**
     * Set questionvalues_id
     * @param string $questionvalues_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     */
    
    public function setQuestionvaluesId($questionvaluesId);

    /**
     * Get question_id
     * @return string|null
     */
    
    public function getQuestionId();

    /**
     * Set question_id
     * @param string $question_id
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     */
    
    public function setQuestionId($question_id);

    /**
     * Get Value
     * @return string|null
     */
    
    public function getValue();

    /**
     * Set Value
     * @param string $Value
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     */
    
    public function setValue($Value);
}
