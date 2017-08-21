<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SuttonSilver\CustomCheckout\Model\Customer;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Attribute;
use Magento\Customer\Model\FileProcessor;
use Magento\Customer\Model\FileProcessorFactory;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\Address\Attribute\Source\CountryWithWebsites;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Eav\Model\Entity\Type;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\DataProvider\EavValidationRules;
use Magento\Customer\Model\ResourceModel\Customer\Collection;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Framework\View\Element\UiComponent\DataProvider\FilterPool;

/**
 * Class DataProvider
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class DataProvider extends \Magento\Customer\Model\Customer\DataProvider
{
	protected function prepareAddressData($addressId, array &$addresses, array $customer)
	{
		parent::prepareAddressData($addressId, $addresses, $customer);

		if (isset($customer['home_address'])
		    && $addressId == $customer['home_address']
		) {
			$addresses[$addressId]['home_address'] = $customer['home_address'];
		}

	}
}