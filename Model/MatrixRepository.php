<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\MatrixRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use SuttonSilver\CustomCheckout\Api\Data\MatrixSearchResultsInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;

class MatrixRepository implements \SuttonSilver\CustomCheckout\Api\MatrixRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    private $storeManager;

    protected $resource;

    protected $dataObjectHelper;

	protected $matrixFactory;
	protected $matrixInterfaceFactory;
    protected $matrixCollectionFactory;


    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix $resource,
        \SuttonSilver\CustomCheckout\Model\MatrixFactory $matrixFactory,
	    \SuttonSilver\CustomCheckout\Api\Data\MatrixInterfaceFactory $matrixInterfaceFactory,
        \SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix\CollectionFactory $matrixCollectionFactory,
        \Magento\Framework\Api\SearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->matrixFactory = $matrixFactory;
	    $this->matrixInterfaceFactory = $matrixInterfaceFactory;
        $this->matrixCollectionFactory = $matrixCollectionFactory;

        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;

        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \SuttonSilver\CustomCheckout\Api\Data\MatrixInterface $matrixInterface
    ) {

        try {
            $this->resource->save($matrixInterface);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Matrix: %1',
                $exception->getMessage()
            ));
        }
        return $matrixInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($matrixId)
    {
        $matrix = $this->matrixFactory->create();
	    $matrix->load($matrixId);
        if (!$matrix->getMatrixId()) {
            throw new NoSuchEntityException(__('Matrix with id "%1" does not exist.', $matrixId));
        }
        return $matrix;
    }

	public function getBySku($matrixSku)
	{
		$matrix = $this->matrixFactory->create();
		$matrix->load($matrixSku, 'product_sku');
		if (!$matrix->getMatrixId()) {
			throw new NoSuchEntityException(__('Matrix with sku "%1" does not exist.', $matrixSku));
		}
		return $matrix;
	}

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->matrixCollectionFactory->create();
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
        
        foreach ($collection as $matrixModel) {
	        $matrixData = $this->matrixInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray(
	            $matrixData,
	            $matrixModel->getData(),
                'SuttonSilver\CustomCheckout\Api\Data\MatrixInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
	            $matrixData,
                'SuttonSilver\CustomCheckout\Api\Data\MatrixInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \SuttonSilver\CustomCheckout\Api\Data\MatrixInterface $matrixInterface
    ) {
        try {
            $this->resource->delete($matrixInterface);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Matrix: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($matrixId)
    {
        return $this->delete($this->getById($matrixId));
    }
}
