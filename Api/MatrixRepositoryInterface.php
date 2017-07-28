<?php


namespace SuttonSilver\CustomCheckout\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface MatrixRepositoryInterface
{


    /**
     * Save QuestionAnswers
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\MatrixInterface $matrix
    );

    /**
     * Retrieve QuestionAnswers
     * @param string $questionanswersId
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getById($matrixId);

	public function getBySku($matrixSku);

    /**
     * Retrieve Matrix matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SuttonSilver\CustomCheckout\Api\Data\MatrixSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Matrix
     * @param \SuttonSilver\CustomCheckout\Api\Data\MatrixInterface $matrix
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\MatrixInterface $matrix
    );

    /**
     * Delete Matrix by ID
     * @param string $matrixId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($matrixId);
}
