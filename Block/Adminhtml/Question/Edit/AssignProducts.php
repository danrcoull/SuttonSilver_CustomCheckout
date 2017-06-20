<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SuttonSilver\CustomCheckout\Block\Adminhtml\Question\Edit;

class AssignProducts extends \Magento\Backend\Block\Template
{

    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'question/edit/assign_products.phtml';

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonEncoder;


    protected $_productCollectionFactory;
    protected $questionFactory;

    /**
     * AssignProducts constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory, //your custom collection
        \SuttonSilver\CustomCheckout\Model\QuestionFactory $questionFactory,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->questionFactory = $questionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {

        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'SuttonSilver\CustomCheckout\Block\Adminhtml\Question\Edit\Tab\Product',
                'question.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getProductsJson()
    {
        $productIds = $this->getQuestion()->getProductSkus();
        if (empty($productIds)) {
            $productIds = 0;
        }
        $vProducts = $this->_productCollectionFactory->create()
            ->addFieldToSelect('product_id')
            ->addFieldToFilter('entity_id',  ['in' => $productIds]);

        $products = array();
        foreach ($vProducts as $pdct) {
            $products[$pdct->getProductId()] = '';
        }

        if (!empty($products)) {
            return $this->jsonEncoder->encode($products);
        }
        return '{}';
    }

    public function getQuestion() {
        $question = $this->questionFactory->create();
        $id = (int)$this->getRequest()->getParam('question_id', false);
        if ($id) {
            $question->load($id);
        }
        if($this->registry->registry('my_question') == null)
        {
            return $question;
        }
        return $this->registry->registry('my_question');
    }
}

