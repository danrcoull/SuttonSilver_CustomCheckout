<?php


namespace SuttonSilver\CustomCheckout\Controller\Adminhtml\Question;

class Delete extends \SuttonSilver\CustomCheckout\Controller\Adminhtml\Question
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('question_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('SuttonSilver\CustomCheckout\Model\Question');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Question.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['question_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Question to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
