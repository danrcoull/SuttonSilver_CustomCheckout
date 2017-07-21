<?php


namespace SuttonSilver\CustomCheckout\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use SuttonSilver\CustomCheckout\Api\QuestionRepositoryInterface;
use SuttonSilver\CustomCheckout\Model\ResourceModel\Question as ResourceQuestion;
use SuttonSilver\CustomCheckout\Api\Data\QuestionInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use SuttonSilver\CustomCheckout\Api\Data\QuestionSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use SuttonSilver\CustomCheckout\Model\ResourceModel\Question\CollectionFactory as QuestionCollectionFactory;

class QuestionRepository implements QuestionRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $questionFactory;

    protected $dataQuestionFactory;

    protected $QuestionCollectionFactory;

    private $storeManager;

    protected $dataObjectHelper;

    protected $resource;


    /**
     * @param ResourceQuestion $resource
     * @param QuestionFactory $questionFactory
     * @param QuestionInterfaceFactory $dataQuestionFactory
     * @param QuestionCollectionFactory $questionCollectionFactory
     * @param QuestionSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceQuestion $resource,
        QuestionFactory $questionFactory,
        QuestionInterfaceFactory $dataQuestionFactory,
        QuestionCollectionFactory $questionCollectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->questionFactory = $questionFactory;
        $this->questionCollectionFactory = $questionCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataQuestionFactory = $dataQuestionFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface $question
    ) {
        /* if (empty($question->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $question->setStoreId($storeId);
        } */
        try {
            $this->resource->save($question);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the question: %1',
                $exception->getMessage()
            ));
        }
        return $question;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($questionId)
    {
        $question = $this->questionFactory->create();
        $question->load($questionId);
        if (!$question->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }
        return $question;
    }

    public function get($field,$value)
    {
        $question = $this->questionFactory->create();
        $question->load($value, $field);
        if (!$question->getId()) {
            throw new NoSuchEntityException(__('Question with id "%1" does not exist.', $questionId));
        }
        return $question;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->questionCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $items = [];
        
        foreach ($collection as $questionModel) {
            $questionData = $this->dataQuestionFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $questionData,
                $questionModel->getData(),
                'SuttonSilver\CustomCheckout\Api\Data\QuestionInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $questionData,
                'SuttonSilver\CustomCheckout\Api\Data\QuestionInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface $question
    ) {
        try {
            $this->resource->delete($question);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Question: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($questionId)
    {
        return $this->delete($this->getById($questionId));
    }
}
