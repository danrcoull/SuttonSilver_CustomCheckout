<?php
namespace SuttonSilver\CustomCheckout\Model\Export;

class Export extends \SuttonSilver\CustomCheckout\Model\Export\ExportAbstract
{
    public function getOrders()
    {
        return $this->OrderCollectionFactory->create()
            ->addAttributeToSelect('*');
    }

    public function stripHouseNumber($street)
    {
	    if ( preg_match('/([^\d]+)\s?(.+)/i', $street, $result) )
	    {
		    return $result;
	    }

	    return false;
    }

    public function runExport()
    {
        $orders= $this->getOrders();
        //$this->logger->addInfo(print_r($orders,));
        $rows = [];
        foreach($orders as $order)
        {


            //createcustomer
	        if($order->getCustomerId()) {
				//create  signiture row
		        $rows[] = $this->getCustomerKeys();

		        $customerObject = $this->customerRepository->getById( $order->getCustomerId() );
		        //prepopulate the array with empty key values
		        $customerArray  = array_fill_keys( $this->getCustomerKeys(), "" );

		        //get home address;
		        $homeAddressId = $customerObject->getCustomAttribute('home_address');
		        if($homeAddressId) {
			        $homeAddress = $this->addressRepository->getById( $homeAddressId );
			        $street      = $this->stripHouseNumber( implode( ',', $homeAddress->getStreet() ) );
			        $number      = isset( $street[0] ) ? $street[0] : false;
			        $address     = isset( $street[1] ) ? explode( ',', $street[1] ) : false;
			        $address1    = isset( $address[0] ) ? $address[0] : false;
			        $address2    = isset( $address[1] ) ? $street[1] : false;
			        $address3    = isset( $address[2] ) ? $address[2] : false;

			        $customerArray['House No'] = ( ( $number !== false ) ? $number : "" );
			        $customerArray['Address1'] = ( ( $address1 !== false ) ? $address1 : "" );
			        $customerArray['Address2'] = ( ( $address2 !== false ) ? $address2 : "" );
			        $customerArray['Address3'] = ( ( $address3 !== false ) ? $address3 : "" );
			        $customerArray['Town']     = $homeAddress->getCity() ?: "";
			        $customerArray['County']   = $homeAddress->getRegion()->getRegion() ?: "";
			        $customerArray['Postcode'] = $homeAddress->getPostcode() ?: "";
			        $customerArray['DX']       = $order->getShippingAddress()->getData( 'dx_number' ) ?: "";
		        }
				//get order datye
		        $customerArray['Date']        = $order->getCreatedAt() ? : "";

		        $customerArray['Title']        = $customerObject->getPrefix() ? : "";
		        $customerArray['Forename']     = $customerObject->getFirstname() ?: "";
		        $customerArray['Surname']      = $customerObject->getLastname() ?: "";
		        $customerArray['DOB']          = $customerObject->getDob() ?: "";
		        $customerArray['Homephone']    = $customerObject->getCustomAttribute( 'daytime_phone_number' )->getValue() ?: "";
		        $customerArray['Mobile']       = $customerObject->getCustomAttribute( 'mobile_number' )->getValue() ?: "";
		        $customerArray['Email']        = $customerObject->getEmail() ?: "";
		        $customerArray['Membership']   = ($membershipNumber = $customerObject->getCustomAttribute( 'membership_number' )) ? $membershipNumber->getValue() : "";
		        $customerArray['Previous CLS'] = ($studiedBefore = $customerObject->getCustomAttribute( 'studied_with_us_before' )) ? $studiedBefore->getValue() : "";

		        $customerArray['Disability']       = $this->findQuestionAnswer( $customerObject->getId(), 'DisabilityAct' );
		        $customerArray['Read Terms']       = 1;
		        $customerArray['Delivery']         = $order->getShippingMethod() ? 1 : 0;
		        $customerArray['Ethnicity Code']   = $this->findQuestionAnswer( $customerObject->getId(), 'Ethnic' );
		        $customerArray['Prev Surname']     = ($previousSurname = $customerObject->getCustomAttribute( 'previous_surname' )) ? $previousSurname->getValue() : "";
		        $customerArray['Prev Postcode']    = ($previousPostcode = $customerObject->getCustomAttribute( 'previous_postcode' )) ? $previousPostcode->getValue() : "";
		        $customerArray['Reason for study'] = $this->findQuestionAnswer( $customerObject->getId(), 'ReasonForStudy' );

		        $rows[] = $customerArray;

		        $itemsObject = $this->getItems( $order );
		        if ( count( $itemsObject ) > 0 ) {
			        $rows[] = $this->getOrderItemKeys();
			        foreach ( $itemsObject as $itemObject ) {
				        $itemRow                = array_fill_keys( $this->getOrderItemKeys(), '' );
				        $itemRow['Description'] = $itemObject->getName();
				        $itemRow['Quantity']    = $itemObject->getQtyOrdered();
				        $itemRow['Price']       = $itemObject->getPrice();
				        $itemRow['Shipping']    = 0;
				        $itemRow['Subtotal']    = $itemObject->getRowTotal();
				        $rows[]                 = $itemRow;
			        }
		        }

		        try {
			        $order->setData( 'export_processed', 1 );
			        $order->save();
		        } catch ( \Exception $e ) {
			        $this->logger->addError( $e->getMessage() );
		        }
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

        $current = $this->questionAnswersRepository->getList($searchCriteriaBuilder)->getItems();
        $value = null;
        foreach ($current as $answerValue)
        {
	        $this->logger->info(print_r($answerValue,true));
            $value = isset($answerValue['value']) ? $answerValue['value'] : null;
        }

        return $value !== null ? $value : $default;
    }
}