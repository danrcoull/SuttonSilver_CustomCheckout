<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\Data\QuestionInterface;

class Question extends \Magento\Framework\Model\AbstractModel implements QuestionInterface
{

    protected $_productsReadOnly = false;
    protected $_questionValues = false;
    protected $_questionValuesRepository = false;


    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SuttonSilver\CustomCheckout\Model\ResourceModel\Question');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_questionValues = $objectManager->get('SuttonSilver\CustomCheckout\Model\QuestionValues');
        $this->_questionValuesRepository = $objectManager->get('SuttonSilver\CustomCheckout\Model\QuestionValuesRepository');
    }

    public function setProductsReadonly($readOnly)
    {
        $this->_productsReadOnly = $readOnly;
    }

    public function getProductsReadonly(){
        return $this->_productsReadOnly;
    }

    /**
     * Get question_id
     * @return string
     */
    public function getQuestionId()
    {
        return $this->getData(self::QUESTION_ID);
    }

    /**
     * Set question_id
     * @param string $questionId
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestionId($questionId)
    {
        return $this->setData(self::QUESTION_ID, $questionId);
    }

    /**
     * Get question
     * @return string
     */
    public function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }

    /**
     * Set question
     * @param string $question
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestion($question)
    {
        return $this->setData(self::QUESTION, $question);
    }

    /**
     * Get question_type
     * @return string
     */
    public function getQuestionType()
    {
        return $this->getData(self::QUESTION_TYPE);
    }

    /**
     * Set question_type
     * @param string $question_type
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestionType($question_type)
    {
        return $this->setData(self::QUESTION_TYPE, $question_type);
    }

    /**
     * Get product_ids
     * @return string
     */
    public function getProductIds()
    {
        if(!is_array($this->getData(self::PRODUCT_IDS))) {
            return explode(',', $this->getData(self::PRODUCT_IDS));
        }

        return $this->getData(self::PRODUCT_IDS);

    }

    /**
     * Set product_ids
     * @param string $product_ids
     * @return SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setProductIds($product_ids)
    {
        if(is_array($product_ids))
        {
            return $this->setData(self::PRODUCT_IDS, implode(',',$product_ids));
        }

        return $this->setData(self::PRODUCT_IDS, $product_ids);
    }


    /**public function setValues($array)
    {
        //remove the original values.
        $collection = $this->_questionValues->getCollection()->addFieldToFilter('question_id',$this->getId());
        foreach($collection as $original)
        {
            $original->delete();
        }



        //loop and recreate
        foreach($array as $value)
        {
            $this->_questionValues->clearInstance();
            $this->_questionValues->setData('question_value',$value['value']);
            $this->_questionValues->setData('question_id',$this->getId());
            $this->_questionValuesRepository->save($this->_questionValues);
        }
    }

    public function getValues()
    {
        $collection = $this->_questionValues->getCollection()->addFieldToFilter('question_id',$this->getId());
        return $collection;
    }
    **/


}
