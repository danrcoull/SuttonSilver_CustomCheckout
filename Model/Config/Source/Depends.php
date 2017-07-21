<?php

namespace SuttonSilver\CustomCheckout\Model\Config\Source;
use Magento\Framework\Data\OptionSourceInterface;

class Depends implements OptionSourceInterface
{
    protected  $collectionFactory;

    public function __construct(
        \SuttonSilver\CustomCheckout\Model\ResourceModel\Question\CollectionFactory $collectionFactory
    )
    {

        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('question_type', array('eq'=>'yes-no'));
        foreach($collection as $question) {
            $options[] =   ['label' => $question->getQuestionName(), 'value' => $question->getQuestionId()];
        }

        return $options;
    }
}