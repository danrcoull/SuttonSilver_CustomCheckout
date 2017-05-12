<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface QuestionValuesSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get QuestionValues list.
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface[]
     */
    
    public function getItems();

    /**
     * Set question_id list.
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
