<?php


namespace SuttonSilver\CustomCheckout\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterfaceFactory;
use SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionValues\CollectionFactory as QuestionValuesCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use SuttonSilver\CustomCheckout\Api\QuestionValuesRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionValues as ResourceQuestionValues;
use SuttonSilver\CustomCheckout\Api\Data\QuestionValuesSearchResultsInterfaceFactory;

class QuestionValuesRepository implements QuestionValuesRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $dataQuestionValuesFactory;

    private $storeManager;

    protected $QuestionValuesCollectionFactory;

    protected $dataObjectHelper;

    protected $resource;

    protected $QuestionValuesFactory;


    /**
     * @param ResourceQuestionValues $resource
     * @param QuestionValuesFactory $questionValuesFactory
     * @param QuestionValuesInterfaceFactory $dataQuestionValuesFactory
     * @param QuestionValuesCollectionFactory $questionValuesCollectionFactory
     * @param QuestionValuesSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceQuestionValues $resource,
        QuestionValuesFactory $questionValuesFactory,
        QuestionValuesInterfaceFactory $dataQuestionValuesFactory,
        QuestionValuesCollectionFactory $questionValuesCollectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->questionValuesFactory = $questionValuesFactory;
        $this->questionValuesCollectionFactory = $questionValuesCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataQuestionValuesFactory = $dataQuestionValuesFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface $questionValues
    ) {
        /* if (empty($questionValues->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $questionValues->setStoreId($storeId);
        } */
        try {
            $this->resource->save($questionValues);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the questionValues: %1',
                $exception->getMessage()
            ));
        }
        return $questionValues;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($questionValuesId)
    {
        $questionValues = $this->questionValuesFactory->create();
        $questionValues->load($questionValuesId);
        if (!$questionValues->getId()) {
            throw new NoSuchEntityException(__('QuestionValues with id "%1" does not exist.', $questionValuesId));
        }
        return $questionValues;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->questionValuesCollectionFactory->create();
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
        
        foreach ($collection as $questionValuesModel) {
            $questionValuesData = $this->dataQuestionValuesFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $questionValuesData,
                $questionValuesModel->getData(),
                'SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $questionValuesData,
                'SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionValuesInterface $questionValues
    ) {
        try {
            $this->resource->delete($questionValues);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the QuestionValues: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($questionValuesId)
    {
        return $this->delete($this->getById($questionValuesId));
    }
}
