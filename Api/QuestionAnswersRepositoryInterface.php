<?php


namespace SuttonSilver\CustomCheckout\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface QuestionAnswersRepositoryInterface
{


    /**
     * Save QuestionAnswers
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
    );

    /**
     * Retrieve QuestionAnswers
     * @param string $questionanswersId
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($questionanswersId);

    /**
     * Retrieve QuestionAnswers matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete QuestionAnswers
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
    );

    /**
     * Delete QuestionAnswers by ID
     * @param string $questionanswersId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($questionanswersId);
}
