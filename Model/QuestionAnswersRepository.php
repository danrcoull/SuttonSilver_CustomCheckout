<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionAnswers as ResourceQuestionAnswers;
use Magento\Framework\Exception\CouldNotSaveException;
use SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterfaceFactory;
use SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersSearchResultsInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use SuttonSilver\CustomCheckout\Api\QuestionAnswersRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use SuttonSilver\CustomCheckout\Model\ResourceModel\QuestionAnswers\CollectionFactory as QuestionAnswersCollectionFactory;

class QuestionAnswersRepository implements QuestionAnswersRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $QuestionAnswersCollectionFactory;

    private $storeManager;

    protected $dataQuestionAnswersFactory;

    protected $QuestionAnswersFactory;

    protected $resource;

    protected $dataObjectHelper;


    /**
     * @param ResourceQuestionAnswers $resource
     * @param QuestionAnswersFactory $questionAnswersFactory
     * @param QuestionAnswersInterfaceFactory $dataQuestionAnswersFactory
     * @param QuestionAnswersCollectionFactory $questionAnswersCollectionFactory
     * @param QuestionAnswersSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceQuestionAnswers $resource,
        QuestionAnswersFactory $questionAnswersFactory,
        QuestionAnswersInterfaceFactory $dataQuestionAnswersFactory,
        QuestionAnswersCollectionFactory $questionAnswersCollectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->questionAnswersFactory = $questionAnswersFactory;
        $this->questionAnswersCollectionFactory = $questionAnswersCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataQuestionAnswersFactory = $dataQuestionAnswersFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
    ) {
        /* if (empty($questionAnswers->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $questionAnswers->setStoreId($storeId);
        } */
        try {
            $this->resource->save($questionAnswers);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the questionAnswers: %1',
                $exception->getMessage()
            ));
        }
        return $questionAnswers;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($questionAnswersId)
    {
        $questionAnswers = $this->questionAnswersFactory->create();
        $questionAnswers->load($questionAnswersId);
        if (!$questionAnswers->getId()) {
            throw new NoSuchEntityException(__('QuestionAnswers with id "%1" does not exist.', $questionAnswersId));
        }
        return $questionAnswers;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->questionAnswersCollectionFactory->create();
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
        
        foreach ($collection as $questionAnswersModel) {
            $questionAnswersData = $this->dataQuestionAnswersFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $questionAnswersData,
                $questionAnswersModel->getData(),
                'SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $questionAnswersData,
                'SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\QuestionAnswersInterface $questionAnswers
    ) {
        try {
            $this->resource->delete($questionAnswers);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the QuestionAnswers: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($questionAnswersId)
    {
        return $this->delete($this->getById($questionAnswersId));
    }
}
