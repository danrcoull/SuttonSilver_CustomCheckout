<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SuttonSilver\CustomCheckout\Model\Attribute\Customer;

/**
 * Customer default shipping address backend
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class HomeAddress extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @param \Magento\Framework\DataObject $object
     * @return void
     */
    public function beforeSave($object)
    {
        $defaultShipping = $object->getData('home_address');

        if ($defaultShipping === null) {
            $object->unsetData('home_address');
        }
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return void
     */
    public function afterSave($object)
    {
	    $defaultShipping = $object->getCustomAttribute('home_address');

        if ($defaultShipping != null) {
	        $addressId = false;

	        /**
	         * post_index set in customer save action for address
	         * this is $_POST array index for address
	         */
	        foreach ( $object->getAddresses() as $address ) {
		        if ( $address->getPostIndex() == $defaultShipping ) {
			        $addressId = $address->getId();
		        }
	        }

	        if ( $addressId ) {

		        $object->setData( 'home_address', $addressId );
		        $this->getAttribute()->getEntity()->saveAttribute( $object, $this->getAttribute()->getAttributeCode() );
	        }
        }






    }
}
