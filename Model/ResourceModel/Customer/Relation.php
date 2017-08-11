<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SuttonSilver\CustomCheckout\Model\ResourceModel\Customer;

/**
 * Class Relation
 */
class Relation extends \Magento\Customer\Model\ResourceModel\Customer\Relation
{
	public function processRelation(\Magento\Framework\Model\AbstractModel $customer)
	{
		$defaultBillingId = $customer->getData('default_billing');
		$defaultShippingId = $customer->getData('default_shipping');
		$homeAddressId = $customer->getData('home_address');

		/** @var \Magento\Customer\Model\Address $address */
		foreach ($customer->getAddresses() as $address) {
			if ($address->getData('_deleted')) {
				if ($address->getId() == $defaultBillingId) {
					$customer->setData('default_billing', null);
				}

				if ($address->getId() == $defaultShippingId) {
					$customer->setData('default_shipping', null);
				}

				if ($address->getId() == $homeAddressId) {
					$customer->setData('home_address', null);
				}

				$removedAddressId = $address->getId();
				$address->delete();

				// Remove deleted address from customer address collection
				$customer->getAddressesCollection()->removeItemByKey($removedAddressId);
			} else {
				$address->setParentId(
					$customer->getId()
				)->setStoreId(
					$customer->getStoreId()
				)->setIsCustomerSaveTransaction(
					true
				)->save();

				if (($address->getIsPrimaryBilling() ||
				     $address->getIsDefaultBilling()) && $address->getId() != $defaultBillingId
				) {
					$customer->setData('default_billing', $address->getId());
				}

				if (($address->getIsPrimaryShipping() ||
				     $address->getIsDefaultShipping()) && $address->getId() != $defaultShippingId
				) {
					$customer->setData('default_shipping', $address->getId());
				}

				if ( $address->getIsHomeAddress() && $address->getId() != $homeAddressId
				) {
					$customer->setData('home_address', $address->getId());
				}

			}
		}

		$changedAddresses = [];

		$changedAddresses['default_billing'] = $customer->getData('default_billing');
		$changedAddresses['default_shipping'] = $customer->getData('default_shipping');
		$changedAddresses['home_address'] = $customer->getData('home_address');

		$customer->getResource()->getConnection()->update(
			$customer->getResource()->getTable('customer_entity'),
			$changedAddresses,
			$customer->getResource()->getConnection()->quoteInto('entity_id = ?', $customer->getId())
		);
	}
}