<?php
namespace SuttonSilver\CustomCheckout\Controller\Adminhtml\Products;


abstract class Product extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'SuttonSilver_CustomCheckout::products';

    protected function _initItem($getRootInstead = false)
    {

        $id = (int)$this->getRequest()->getParam('question_id', false);
        $myModel = $this->_objectManager->create('SuttonSilver\CustomCheckout\Model\Question');

        if ($id) {
            $myModel->load($id);
        }

        $this->_objectManager->get('Magento\Framework\Registry')->register('question', $myModel);
        $this->_objectManager->get('Magento\Framework\Registry')->register('my_question', $myModel);
        $this->_objectManager->get('Magento\Cms\Model\Wysiwyg\Config');
        return $myModel;
    }

}