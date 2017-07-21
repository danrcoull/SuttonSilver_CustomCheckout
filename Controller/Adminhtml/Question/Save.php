<?php


namespace SuttonSilver\CustomCheckout\Controller\Adminhtml\Question;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    )
    {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('question_id');

            $model = $this->_objectManager->create('SuttonSilver\CustomCheckout\Model\Question')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Question no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }


            if (isset($data['question_products'])) {
                $ids = array();
                foreach (json_decode($data['question_products']) as $key => $val) {
                    if ($key != '_empty_') {
                        $ids[] = $key;
                    }
                }

                $data['product_skus'] = $ids;
            }

            $model->setData($data);
            if (isset($data['question_options_select'])) :
                $model->setValues($data['question_options_select']);
            endif;
            $model->setProductSkus($data['product_skus']);

            if(isset($data['question_depends_on'])) {
                $model->setQuestionDependsOn($data['question_depends_on']);
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Question.'));
                $this->dataPersistor->clear('suttonsilver_customcheckout_question');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['question_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Question.'));
            }

            $this->dataPersistor->set('suttonsilver_customcheckout_question', $data);
            return $resultRedirect->setPath('*/*/edit', ['question_id' => $this->getRequest()->getParam('question_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
