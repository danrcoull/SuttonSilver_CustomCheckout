<?php


namespace SuttonSilver\CustomCheckout\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionValuesRepositoryInterface
{


    /**
     * Save QuestionValues
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface $questionValues
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface $questionValues
    );

    /**
     * Retrieve QuestionValues
     * @param string $questionvaluesId
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($questionvaluesId);

    /**
     * Retrieve QuestionValues matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete QuestionValues
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface $questionValues
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface $questionValues
    );

    /**
     * Delete QuestionValues by ID
     * @param string $questionvaluesId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($questionvaluesId);
}
