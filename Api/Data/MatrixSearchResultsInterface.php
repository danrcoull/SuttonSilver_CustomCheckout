<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface MatrixSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Question list.
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface[]
     */
    
    public function getItems();

    /**
     * Set question list.
     * @param \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
