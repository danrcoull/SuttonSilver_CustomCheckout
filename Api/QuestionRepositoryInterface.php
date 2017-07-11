<?php


namespace SuttonSilver\CustomCheckout\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionRepositoryInterface
{


    /**
     * Save Question
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface $question
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface $question
    );

    /**
     * Retrieve Question
     * @param string $questionId
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($questionId);


    /**
     * Retrieve Question
     * @param string $field
     * @param string $value
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function get($field,$value);

    /**
     * Retrieve Question matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Question
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface $question
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface $question
    );

    /**
     * Delete Question by ID
     * @param string $questionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($questionId);
}
