<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product in category grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace SuttonSilver\CustomCheckout\Block\Adminhtml\Question\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class Product extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $logger;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_questionFactory;


    protected $_productCollectionFactory;


    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \SuttonSilver\CustomCheckout\Model\QuestionFactory $questionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_questionFactory = $questionFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('question_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * @return array|null
     */
    public function getQuestion() {
        $question = $this->_questionFactory->create();
        $id = (int)$this->getRequest()->getParam('question_id', false);
        if ($id) {
            $question->load($id);
        }
        if($this->_coreRegistry->registry('my_question') == null)
        {
            return $question;
        }
        return $this->_coreRegistry->registry('my_question');
    }

    /**
     * @param Column $column
     * @return $this**/

    protected function _addColumnFilterToCollection($column)
    {

        // Set custom filter for in category flag
        if ($column->getId() == 'in_question') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getQuestion()->getId()) {
            $this->setDefaultFilter(['in_question' => 1]);
        }
        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'price'
        );
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        if ($storeId > 0) {
            $collection->addStoreFilter($storeId);
        }


        $this->setCollection($collection);

        if ($this->getQuestion()->getProductsReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
        }


        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        if (!$this->getQuestion()->getProductsReadonly()) {
            $this->addColumn(
                'in_question',
                [
                    'type' => 'checkbox',
                    'name' => 'in_question',
                    'values' => $this->_getSelectedProducts(),
                    'index' => 'entity_id',
                    'header_css_class' => 'col-select col-massaction',
                    'column_css_class' => 'col-select col-massaction'
                ]
            );
        }
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'currency_code' => (string)$this->_scopeConfig->getValue(
                    \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'index' => 'price'
            ]
        );
        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'type' => 'number',
                'index' => 'position',
                'editable' => !$this->getQuestion()->getProductsReadonly()
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {

        return $this->getUrl('suttonsilver_customcheckout/products/grid', ['_current' => true]);
    }

    /**
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if ($products === null) {
            $productIds = $this->getQuestion()->getProductSkus();
            if (empty($productIds)) {
                $productIds = 0;
            }
            $vProducts = $this->_productCollectionFactory->create()
                ->addFieldToSelect('product_id')
                ->addFieldToFilter('entity_id',  ['in' => $productIds]);


            $products = array();
            foreach($vProducts as $pdct){
                $products[]  = $pdct->getProductId();
            }
        }
        //die(var_dump( $this->getQuestion()->getData() ));
        return $products;
    }
}
