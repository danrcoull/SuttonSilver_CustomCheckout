<?php


namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\Data\QuestionInterface;

class Question extends \Magento\Framework\Model\AbstractModel implements QuestionInterface
{

    protected $_productsReadOnly = false;
    protected $_objectManager = false;
    protected $_questionValues = false;
    protected $_questionValuesRepository = false;


    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('SuttonSilver\CustomCheckout\Model\ResourceModel\Question');
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_questionValues = $this->_objectManager->create('SuttonSilver\CustomCheckout\Model\QuestionValues');
        $this->_questionValuesRepository = $this->_objectManager->create('SuttonSilver\CustomCheckout\Model\QuestionValuesRepository');
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
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
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
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
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
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setQuestionType($question_type)
    {
        return $this->setData(self::QUESTION_TYPE, $question_type);
    }

    /**
     * Get product_ids
     * @return string
     */
    public function getProductSkus()
    {
        if(!is_array($this->getData(self::PRODUCT_IDS))) {
            return explode(',', $this->getData(self::PRODUCT_IDS));
        }

        return $this->getData(self::PRODUCT_IDS);

    }

    /**
     * Set product_ids
     * @param string $product_ids
     * @return \SuttonSilver\CustomCheckout\Api\Data\QuestionInterface
     */
    public function setProductSkus($product_ids)
    {
        if(is_array($product_ids))
        {
            return $this->setData(self::PRODUCT_IDS, implode(',',$product_ids));
        }

        return $this->setData(self::PRODUCT_IDS, $product_ids);
    }


    public function setValues($array)
    {
        //die(var_dump($array));
        //remove the original values.
        $collection = $this->_questionValues->getCollection()->addFieldToFilter('question_id',$this->getId());
        foreach($collection as $original)
        {
            $original->delete();
        }



        //loop and recreate
        foreach($array as $value)
        {
            $valueSaved = $value['question_answer_saved_value'] != '' ? $value['question_answer_saved_value'] : $value['question_answer_value'];
            $this->_questionValues = $this->_objectManager->create('SuttonSilver\CustomCheckout\Model\QuestionValues');
            $this->_questionValues->setData('question_value',$value['question_answer_value']);
            $this->_questionValues->setData('question_saved_value',$valueSaved);
            $this->_questionValues->setData('question_id',$this->getId());
            $this->_questionValuesRepository->save($this->_questionValues);
            //var_dump($this->_questionValues->getData());
        }
        //die;
    }

    public function getValues()
    {
        $collections = $this->_questionValues->getCollection()->addFieldToFilter('question_id', $this->getId());
        $array = [];
        foreach($collections as $collection)
        {
            $array['question_options_select'][] = [
                'question_answer_value' =>  $collection->getData('question_value'),
                'question_answer_saved_value' =>$collection->getData('question_saved_value')
                ];
        }
        return $array;
    }

    public function getQuestionName()
    {
        return $this->getData(self::QUESTION_NAME);
    }

    public function setQuestionName($question_name)
    {
        return $this->setData(self::QUESTION_NAME, $question_name);
    }

    public function getQuestionIsRequired()
    {
        return $this->getData(self::QUESTION_IS_REQUIRED);
    }

    public function setQuestionIsRequired($question_is_required)
    {
        return $this->setData(self::QUESTION_IS_REQUIRED, filter_var($question_is_required, FILTER_VALIDATE_BOOLEAN));
    }

    public function getQuestionPlaceholder()
    {
        return $this->getData(self::QUESTION_PLACEHOLDER);
    }

    public function setQuestionPlaceholder($question_placeholder)
    {
        return $this->setData(self::QUESTION_PLACEHOLDER, $question_placeholder);
    }

    public function getQuestionIsActive()
    {
        return $this->getData(self::QUESTION_ACTIVE);
    }

    public function setQuestionIsActive($question_is_active)
    {
        return $this->setData(self::QUESTION_ACTIVE, $question_is_active);
    }

    public function getQuestionDependsOn()
    {
        if(!is_array($this->getData(self::QUESTION_DEPENDS_ON))) {
            return explode(',', $this->getData(self::QUESTION_DEPENDS_ON));
        }

        return $this->getData(self::QUESTION_DEPENDS_ON);
    }

    public function setQuestionDependsOn($question_depends_on)
    {
        if(is_array($question_depends_on))
        {
            $question_depends_on = implode(',',$question_depends_on);
        }

        return $this->setData(self::QUESTION_DEPENDS_ON, $question_depends_on);
    }


}
