<?php


namespace SuttonSilver\CustomCheckout\Model\Question;

use Magento\Framework\App\Request\DataPersistorInterface;
use SuttonSilver\CustomCheckout\Model\ResourceModel\Question\CollectionFactory;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Api\StoreRepositoryInterface;

use Magento\Ui\Component\Form;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\DataType\Text;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $loadedData;

    /**
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreRepositoryInterface $storeRepository,
        ArrayManager $arrayManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->arrayManager = $arrayManager;
        $this->storeRepository = $storeRepository;

    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
            $values = $model->getValues();
            $this->loadedData[$model->getId()] = array_merge($this->loadedData[$model->getId()], $values);
        }
        $data = $this->dataPersistor->get('suttonsilver_customcheckout_question');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);

            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('suttonsilver_customcheckout_question');
        }

        //print_r($this->loadedData);
        return $this->loadedData;
    }







}
