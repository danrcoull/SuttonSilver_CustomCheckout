<?php
namespace SuttonSilver\CustomCheckout\Model\Export;
class Export extends \SuttonSilver\CustomCheckout\Model\Export\ExportAbstract
{
    public function getOrders()
    {
        return $this->OrderCollectionFactory->create()
            ->addAttributeToSelect('*');
           // ->addFieldToFilter('export_processed', array('neq'=>1));
    }

    public function runExport()
    {
        $orders= $this->getOrders();
        //$this->logger->addInfo(print_r($orders));
        $rows = [];
        foreach($orders as $order)
        {

            //create  signiture row
            $rows[] = $this->getCustomerKeys();

            //createcustomer
            $customerObject = $this->customerRepository->getById($order->getCustomerId());
            $customerArray = array_fill_keys($this->getCustomerKeys(),'');

            $customerArray['Title'] = $customerObject->getPrefix();
            $customerArray['Forename'] = $customerObject->getFirstname();
            $customerArray['Surname'] = $customerObject->getLastname();
            $customerArray['DOB'] = $customerObject->getDob();
            $customerArray['Homephone'] = $customerObject->getCustomAttribute('daytime_phone_number')->getValue();
            $customerArray['Mobile'] = $customerObject->getCustomAttribute('mobile_number')->getValue();
            $customerArray['Email'] = $customerObject->getEmail();
            $customerArray['Membership'] = $customerObject->getCustomAttribute('membership_number')->getValue();
            $customerArray['Previous CLS'] = $customerObject->getCustomAttribute('studied_with_us_before')->getValue();


            $customerArray['Disability'] = $this->findQuestionAnswer($customerObject->getId(), 'DisabilityAct' );
            $customerArray['Read Terms'] = 1;
            $customerArray['Delivery'] =  $order->getShippingMethod() ? 1 : 0;
            $customerArray['Ethnicity Code'] = $this->findQuestionAnswer($customerObject->getId(), 'Ethnic' );
            $customerArray['Prev Surname'] = $customerObject->getCustomAttribute('previous_surname')->getValue();
            $customerArray['Prev Postcode'] = $customerObject->getCustomAttribute('previous_postcode')->getValue();
            $customerArray['Reason for study'] = $this->findQuestionAnswer($customerObject->getId(), 'ReasonForStudy' );

            $rows[] = $customerArray;

            $itemsObject = $this->getItems($order);
            if(count($itemsObject) > 0) {
                $rows[] = $this->getOrderItemKeys();
                foreach ($itemsObject as $itemObject) {
                    $itemRow = array_fill_keys($this->getOrderItemKeys(),'');
                    $itemRow['Description'] = $itemObject->getName();
                    $itemRow['Quantity'] = $itemObject->getQtyOrdered();
                    $itemRow['Price'] = $itemObject->getPrice();
                    $itemRow['Shipping'] = 0;
                    $itemRow['Subtotal'] = $itemObject->getRowTotal();
                    $rows[] = $itemRow;
                }
            }

            try {
                $order->setData('export_processed', 1);
                $order->save();
            }catch(\Exception $e)
            {
                $this->logger->addError($e->getMessage());
            }
        }

        $this->saveExport($rows);


    }

    public function getItems($order)
    {
        return $order->getAllVisibleItems();
    }

    public function findQuestionAnswer($customerId, $questioname,$type='string')
    {
        $question = $this->questionRepository->get('question_name', $questioname);
        $default = $type=='string' ? "" : 0;

        if(!$question->getQuestionId())
        {
            return $default;
        }

        $searchCriteriaBuilder = $this->searchCriteriaBuilder
            ->addFilter(
                'question_id',
                $question->getQuestionId(),
                'eq'
            )->addFilter(
                'customer_id',
                $customerId,
                'eq'
            )->create();

        $current = $this->questionAnswersRepository->getList($searchCriteriaBuilder);
        $value = null;
        foreach ($current as $answerValue)
        {
            $value = $answerValue->getValue();
        }

        return $value !== null ? $value : $default;
    }
}