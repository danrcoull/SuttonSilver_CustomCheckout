<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionAnswersSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get QuestionAnswers list.
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface[]
     */
    
    public function getItems();

    /**
     * Set customer_id list.
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
