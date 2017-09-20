<?php
namespace SuttonSilver\CustomCheckout\Model\Export;

class Export extends \SuttonSilver\CustomCheckout\Model\Export\ExportAbstract
{
    public function getOrders()
    {
        return $this->OrderCollectionFactory->create()
            ->addAttributeToSelect('*')
	        ->addFieldToFilter(
		        'status',
		        ['in' => ['processing','complete']]
	        )
	        ->setOrder(
		        'created_at',
		        'desc'
	        );;
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
				if($customerObject) {
					//$customerArray  = array_fill_keys( $this->getCustomerKeys(), "" );
					$customerArray = [];
					//get home address;
					$homeAddressId = $customerObject->getCustomAttribute( 'home_address' );

					$this->logger->addInfo(print_r($homeAddressId,true));
					if ( $homeAddressId->getValue()) {
						try {
							$homeAddress = $this->addressRepository->getById( $homeAddressId->getValue() );
							$street      = $this->stripHouseNumber( implode( ',', $homeAddress->getStreet() ) );
							$number      = isset( $street[0] ) ? $street[0] : "";
							$address     = isset( $street[1] ) ? explode( ',', $street[1] ) : explode( ',', $street[0] );
							$address1    = isset( $address[0] ) ? $address[0] : "";
							$address2    = isset( $address[1] ) ? $address[1] : "";
							$address3    = isset( $address[2] ) ? $address[2] : "";

							$customerArray[6]  = ( ( $number !== false ) ? $number : "" );
							$customerArray[7]  = ( ( $address1 !== false ) ? $address1 : "" );
							$customerArray[8]  = ( ( $address2 !== false ) ? $address2 : "" );
							$customerArray[9]  = ( ( $address3 !== false ) ? $address3 : "" );
							$customerArray[10] = $homeAddress->getCity() ?: "";
							$customerArray[11] = $homeAddress->getRegion()->getRegion() ?: "";
							$customerArray[12] = $homeAddress->getPostcode() ?: "";
						}catch(\Exception $e)
						{
							$customerArray[6]  = "";
							$customerArray[7]  = "";
							$customerArray[8]  = "";
							$customerArray[9]  = "";
							$customerArray[10] = "";
							$customerArray[11] = "";
							$customerArray[12] = "";
						}

					}else {
						$customerArray[6]  = "";
						$customerArray[7]  = "";
						$customerArray[8]  = "";
						$customerArray[9]  = "";
						$customerArray[10] = "";
						$customerArray[11] = "";
						$customerArray[12] = "";
					}


					if($shippingAddress = $order->getShippingAddress())
					{
						//$shippingAddress = $this->addressRepository->getById( ( ( $address2 !== false ) ? $address2 : "" );->getId() );

						$address     = explode( ',', implode(',',$shippingAddress->getStreet()));
						$address1    = isset( $address[0] ) ? $address[0] : "";
						$address2    = isset( $address[1] ) ? $address[1] : "";
						$address3    = isset( $address[2] ) ? $address[2] : "";

						$customerArray[20] = ( ( $address1 !== false ) ? $address1 : "" );
						$customerArray[21] = ( ( $address2 !== false ) ? $address2 : "" );
						$customerArray[22] = ( ( $address3 !== false ) ? $address3 : "" );

						$customerArray[23]     = $shippingAddress->getCity() ?: "";
						$customerArray[24]   = $shippingAddress->getRegion()?: "";
						$customerArray[25] = $shippingAddress->getPostcode() ?: "";
						$customerArray[26] = $shippingAddress->getTelephone() ?: "";
						$customerArray[27]       = $shippingAddress->getData( 'dx_number' ) ?: "";
					}
					//get order datye
					$customerArray[5] = $order->getCreatedAt() ?: "";

					$customerArray[0] = "#".$order->getId();
					$customerArray[1]        = $customerObject->getPrefix() ?: "";
					$customerArray[2]     = $customerObject->getFirstname() ?: "";
					$customerArray[3]      = $customerObject->getLastname() ?: "";
					$customerArray[4]          = $customerObject->getDob() ?: "";
					$customerArray[13]    = ($attr = $customerObject->getCustomAttribute( 'daytime_phone_number' )) ? $attr->getValue() : "";
					$customerArray[14]       = ($attr = $customerObject->getCustomAttribute( 'mobile_number' )) ? $attr->getValue() : "";
					$customerArray[15]        = $customerObject->getEmail() ?: "";
					$customerArray[16]   = ( $membershipNumber = $customerObject->getCustomAttribute( 'membership_number' ) ) ? $membershipNumber->getValue() : "";
					$customerArray[17] = ( $studiedBefore = $customerObject->getCustomAttribute( 'studied_with_us_before' ) ) ? $studiedBefore->getValue() : "";
					$customerArray[18] = "";
					$customerArray[19] = "";

					$customerArray[28] = ($previousCls= $customerObject->getCustomAttribute('studied_with_us_before')) ? $previousCls->getValue() : "false";

					$customerArray[29]       = ($disability = $this->findQuestionAnswer( $customerObject->getId(), 'DisabilityAct' )) ? $disability : 0;
					$customerArray[30]       = 1;
					$customerArray[31]       = $order->getShippingMethod() ? 1 : 0;
					$customerArray[32]       = ($gender = $customerObject->getGender() ) ? $gender : "";

					$customerArray[33]    = $this->findQuestionAnswer( $customerObject->getId(), 'Ethnic' );
					$customerArray[34] = "";
					$customerArray[35] = "";
					$customerArray[36]     = ( $previousSurname = $customerObject->getCustomAttribute( 'previous_surname' ) ) ? $previousSurname->getValue() : "";
					$customerArray[37] ="";
					$customerArray[38] = "";
					$customerArray[39] = "";
					$customerArray[40] = "";
					$customerArray[41] = "";
					$customerArray[42] = "";
					$customerArray[43] = "";
					$customerArray[44]    = ( $previousPostcode = $customerObject->getCustomAttribute( 'previous_postcode' ) ) ? $previousPostcode->getValue() : "";
					$customerArray[45] = "";
					$customerArray[46] = $this->findQuestionAnswer( $customerObject->getId(), 'ReasonForStudy' );


					$this->logger->addInfo(print_r($customerArray,true));
					ksort($customerArray);
					$rows[] = $customerArray;



					$itemsObject = $this->getItems( $order );
					if ( count( $itemsObject ) > 0 ) {
						$rows[] = $this->getOrderItemKeys();
						foreach ( $itemsObject as $itemObject ) {
							$itemRow                = array_fill_keys( $this->getOrderItemKeys(), '' );

							$rowTotal =     $itemObject->getRowTotal() +
											$itemObject->getTaxAmount() +
							                $itemObject->getDiscountTaxCompensationAmount() -
							                $itemObject->getDiscountAmount();

							$itemRow['Description'] = $itemObject->getName();
							$itemRow['Quantity']    = $itemObject->getQtyOrdered();
							$itemRow['Price']       = $itemObject->getPrice() + $itemObject->getTaxAmount();
							$itemRow['Shipping']    = 0;
							$itemRow['Subtotal']    = $rowTotal;
							$rows[]                 = $itemRow;
						}

						$rows[]   = ["", "", "", "", $order->getGrandTotal(), ""];
						$rows[]   = ["","","","","",""];
						$rows[]   = ["","","","","",""];
					}

					try {
						$order->setData( 'export_processed', 1 );
						$order->save();
					} catch ( \Exception $e ) {
						$this->logger->addError( $e->getMessage() );
					}
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