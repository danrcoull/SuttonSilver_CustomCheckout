<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionInterface
{

    const PRODUCT_IDS = 'product_skus';
    const QUESTION_ID = 'question_id';
    const QUESTION = 'question';
    const QUESTION_TYPE = 'question_type';
    const QUESTION_NAME = 'question_name';
    const QUESTION_PLACEHOLDER = 'question_placeholder';
    const QUESTION_IS_REQUIRED = 'question_is_required';
    const QUESTION_ACTIVE = 'question_is_active';
    const QUESTION_DEPENDS_ON = 'question_depends_on';
    const QUESTION_TOOLTIP = 'question_tooltip';
    const QUESTION_POSITION = 'question_position';


    /**
     * Get question_id
     * @return string|null
     */
    
    public function getQuestionId();

    /**
     * Set question_id
     * @param string $question_id
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQuestionId($questionId);

    /**
     * Get questions_is_active
     * @return string|null
     */

    public function getQuestionIsActive();

    /**
     * Set questions_is_active
     * @param string $questions_is_active
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */

    public function setQuestionIsActive($question_is_active);

    /**
     * Get question
     * @return string|null
     */
    
    public function getQuestion();

    /**
     * Set question
     * @param string $question
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
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
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setQuestionType($question_type);

    /**
     * Get product_ids
     * @return array|null
     */
    
    public function getProductSkus();

    /**
     * Set product_ids
     * @param object $product_ids
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    
    public function setProductSkus($product_ids);

    /**
     * Get question_name
     * @return string|null
     */

    public function getQuestionName();

    /**
     * Set question_name
     * @param string $question_name
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */

    public function setQuestionName($question_name);

    /**
     * Get question_is_required
     * @return string|null
     */

    public function getQuestionIsRequired();

    /**
     * Set question_is_required
     * @param string $question_is_required
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */

    public function setQuestionIsRequired($question_is_required);

    /**
     * Get question_placeholder
     * @return string|null
     */

    public function getQuestionPlaceholder();

    /**
     * Set question_placeholder
     * @param string $question_is_required
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */

    public function setQuestionPlaceholder($question_placeholder);

    /**
     * Get question_depends_on
     * @return array|null
     */

    public function getQuestionDependsOn();

    /**
     * Set question_depends_on
     * @param object $question_depends_on
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */

    public function setQuestionDependsOn($question_depends_on);


	/**
	 * Get question_tooltip
	 * @return string|null
	 */

	public function getQuestionTooltip();

	/**
	 * Set question_tooltip
	 * @param string $value
	 * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
	 */

	public function setQuestionTooltip($tooltip);

	/**
	 * Get question_position
	 * @return string|null
	 */

	public function getQuestionPosition();

	/**
	 * Set question_position
	 * @param string $value
	 * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
	 */

	public function setQuestionPosition($tooltip);
}
