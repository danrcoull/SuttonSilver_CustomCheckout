<?php


namespace SuttonSilver\CustomCheckout\Controller\Adminhtml;

abstract class Question extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'SuttonSilver_CustomCheckout::top_level';
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('SuttonSilver'), __('SuttonSilver'))
            ->addBreadcrumb(__('Question'), __('Question'));
        return $resultPage;
    }
}
