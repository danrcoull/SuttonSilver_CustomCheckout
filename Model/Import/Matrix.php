<?php
namespace SuttonSilver\CustomCheckout\Model\Import;

use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;

class Matrix extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{

	const MATRIX_ID = 'cls_matrix_id';
	const PRODUCT_SKU = 'product_sku';
	const DESTINATION = 'destination';
	const SINGLE_PRICE = 'single_price';
	const INCREMENT_PRICE = 'increment_price';
	const MAX_PRICE = 'max_price';
	const NO_OVERSEAS = 'no_overseas';
	const ASSOCIATED_SKUS = 'associated_skus';

	const TABLE_Entity = 'suttonsilver_cls_matrix';

	/**
	 * Validation failure message template definitions
	 *
	 * @var array
	 */
	protected $_messageTemplates = [
		'sku_empty' => 'Product SKU Empty',
	];


	/**
	 * If we should check column names
	 *
	 * @var bool
	 */
	protected $needColumnCheck = false;
	protected $groupFactory;
	/**
	 * Valid column names
	 *
	 * @array
	 */
	protected $validColumnNames = [
		self::MATRIX_ID,
		self::PRODUCT_SKU,
		self::DESTINATION,
		self::SINGLE_PRICE,
		self::INCREMENT_PRICE,
		self::MAX_PRICE,
		self::NO_OVERSEAS,
		self::ASSOCIATED_SKUS
	];

	/**
	 * Need to log in import history
	 *
	 * @var bool
	 */
	protected $logInHistory = true;

	protected $_validators = [];


	/**
	 * @var \Magento\Framework\Stdlib\DateTime\DateTime
	 */
	protected $_connection;
	protected $_resource;

	/**
	 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
	 */
	public function __construct(
		\Magento\Framework\Json\Helper\Data $jsonHelper,
		\Magento\ImportExport\Helper\Data $importExportData,
		\Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
		\Magento\Framework\App\ResourceConnection $resource,
		\Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
		\Magento\Framework\Stdlib\StringUtils $string,
		ProcessingErrorAggregatorInterface $errorAggregator,
		\Magento\Customer\Model\GroupFactory $groupFactory
	) {
		$this->jsonHelper = $jsonHelper;
		$this->_importExportData = $importExportData;
		$this->_resourceHelper = $resourceHelper;
		$this->_dataSourceModel = $importData;
		$this->_resource = $resource;
		$this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
		$this->errorAggregator = $errorAggregator;
		$this->groupFactory = $groupFactory;
	}
	public function getValidColumnNames()
	{
		return $this->validColumnNames;
	}

	/**
	 * Entity type code getter.
	 *
	 * @return string
	 */
	public function getEntityTypeCode()
	{
		return self::TABLE_Entity;
	}

	/**
	 * Row validation.
	 *
	 * @param array $rowData
	 * @param int $rowNum
	 * @return bool
	 */
	public function validateRow(array $rowData, $rowNum)
	{

		$title = false;

		if (isset($this->_validatedRows[$rowNum])) {
			return !$this->getErrorAggregator()->isRowInvalid($rowNum);
		}

		$this->_validatedRows[$rowNum] = true;
		// BEHAVIOR_DELETE use specific validation logic
		// if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
		if (!isset($rowData[self::PRODUCT_SKU]) || empty($rowData[self::PRODUCT_SKU])) {
			$this->addRowError('Product SKU is required', $rowNum);
			return false;
		}


		return !$this->getErrorAggregator()->isRowInvalid($rowNum);
	}


	/**
	 * Create Advanced price data from raw data.
	 *
	 * @throws \Exception
	 * @return bool Result of operation.
	 */
	protected function _importData()
	{
		if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
			$this->deleteEntity();
		} elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()) {
			$this->replaceEntity();
		} elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()) {
			$this->saveEntity();
		}

		return true;
	}
	/**
	 * Save newsletter subscriber
	 *
	 * @return $this
	 */
	public function saveEntity()
	{
		$this->saveAndReplaceEntity();
		return $this;
	}
	/**
	 * Replace newsletter subscriber
	 *
	 * @return $this
	 */
	public function replaceEntity()
	{
		$this->saveAndReplaceEntity();
		return $this;
	}
	/**
	 * Deletes newsletter subscriber data from raw data.
	 *
	 * @return $this
	 */
	public function deleteEntity()
	{
		$listTitle = [];
		while ($bunch = $this->_dataSourceModel->getNextBunch()) {
			foreach ($bunch as $rowNum => $rowData) {
				$this->validateRow($rowData, $rowNum);
				if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
					$rowTtile = $rowData[self::PRODUCT_SKU];
					$listTitle[] = $rowTtile;
				}
				if ($this->getErrorAggregator()->hasToBeTerminated()) {
					$this->getErrorAggregator()->addRowToSkip($rowNum);
				}
			}
		}
		if ($listTitle) {
			$this->deleteEntityFinish(array_unique($listTitle),self::TABLE_Entity);
		}
		return $this;
	}
	/**
	 * Save and replace newsletter subscriber
	 *
	 * @return $this
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 * @SuppressWarnings(PHPMD.NPathComplexity)
	 */
	protected function saveAndReplaceEntity()
	{
		$behavior = $this->getBehavior();
		$listTitle = [];
		while ($bunch = $this->_dataSourceModel->getNextBunch()) {
			$entityList = [];
			foreach ($bunch as $rowNum => $rowData) {
				if (!$this->validateRow($rowData, $rowNum)) {
					continue;
				}
				if ($this->getErrorAggregator()->hasToBeTerminated()) {
					$this->getErrorAggregator()->addRowToSkip($rowNum);
					continue;
				}

				$rowTtile= $rowData[self::PRODUCT_SKU];
				$listTitle[] = $rowTtile;
				$entityList[$rowTtile][] = [
					self::PRODUCT_SKU => $rowData[self::PRODUCT_SKU],
					self::DESTINATION => $rowData[self::DESTINATION],
					self::SINGLE_PRICE => $rowData[self::SINGLE_PRICE],
					self::INCREMENT_PRICE => $rowData[self::INCREMENT_PRICE],
					self::MAX_PRICE => $rowData[self::MAX_PRICE],
					self::NO_OVERSEAS => $rowData[self::NO_OVERSEAS],
					self::ASSOCIATED_SKUS => $rowData[self::ASSOCIATED_SKUS]
				];
			}
			if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
				if ($listTitle) {
					if ($this->deleteEntityFinish(array_unique(  $listTitle), self::TABLE_Entity)) {
						$this->saveEntityFinish($entityList, self::TABLE_Entity);
					}
				}
			} elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
				$this->saveEntityFinish($entityList, self::TABLE_Entity);
			}
		}
		return $this;
	}
	/**
	 * Save product prices.
	 *
	 * @param array $priceData
	 * @param string $table
	 * @return $this
	 */
	protected function saveEntityFinish(array $entityData, $table)
	{
		if ($entityData) {
			$tableName = $this->_connection->getTableName($table);
			$entityIn = [];
			foreach ($entityData as $id => $entityRows) {
				foreach ($entityRows as $row) {
					$entityIn[] = $row;
				}
			}
			if ($entityIn) {
				$this->_connection->insertOnDuplicate($tableName, $entityIn,$this->validColumnNames);
			}
		}
		return $this;
	}
	protected function deleteEntityFinish(array $listTitle, $table)
	{
		if ($table && $listTitle) {
			try {
				$this->countItemsDeleted += $this->_connection->delete(
					$this->_connection->getTableName($table),
					$this->_connection->quoteInto('product_sku IN (?)', $listTitle)
				);
				return true;
			} catch (\Exception $e) {
				return false;
			}

		} else {
			return false;
		}
	}
}