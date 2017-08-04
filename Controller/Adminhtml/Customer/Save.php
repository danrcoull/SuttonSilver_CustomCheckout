<?php

namespace SuttonSilver\CustomCheckout\Controller\Adminhtml\Customer;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Controller\RegistryConstants;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Customer\Model\Metadata\Form;
use Magento\Framework\Exception\LocalizedException;

class Save extends  \Magento\Customer\Controller\Adminhtml\Index\Save
{

    protected function _extractCustomerData()
    {
        $customerData = [];
        if ($this->getRequest()->getPost('customer')) {
            $additionalAttributes = [
                CustomerInterface::DEFAULT_BILLING,
                CustomerInterface::DEFAULT_SHIPPING,
                'confirmation',
                'sendemail_store_id',
                'extension_attributes',
                'home_address'
            ];

            $customerData = $this->_extractData(
                $this->getRequest(),
                'adminhtml_customer',
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                $additionalAttributes,
                'customer'
            );
        }

        if (isset($customerData['disable_auto_group_change'])) {
            $customerData['disable_auto_group_change'] = (int) filter_var(
                $customerData['disable_auto_group_change'],
                FILTER_VALIDATE_BOOLEAN
            );
        }

        return $customerData;
    }

    protected function saveDefaultFlags(array $addressIdList, array & $extractedCustomerData)
    {

        $result = [];
        $extractedCustomerData[CustomerInterface::DEFAULT_BILLING] = null;
        $extractedCustomerData[CustomerInterface::DEFAULT_SHIPPING] = null;
        $extractedCustomerData['home_address'] = null;

        foreach ($addressIdList as $addressId) {
            $scope = sprintf('address/%s', $addressId);
            $addressData = $this->_extractData(
                $this->getRequest(),
                'adminhtml_customer_address',
                AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
                ['default_billing', 'default_shipping','home_address'],
                $scope
            );

            if (is_numeric($addressId)) {
                $addressData['id'] = $addressId;
            }
            // Set default billing and shipping flags to customer
            if (!empty($addressData['default_billing']) && $addressData['default_billing'] === 'true') {
                $extractedCustomerData[CustomerInterface::DEFAULT_BILLING] = $addressId;
                $addressData['default_billing'] = true;
            } else {
                $addressData['default_billing'] = false;
            }
            if (!empty($addressData['default_shipping']) && $addressData['default_shipping'] === 'true') {
                $extractedCustomerData[CustomerInterface::DEFAULT_SHIPPING] = $addressId;
                $addressData['default_shipping'] = true;
            } else {
                $addressData['default_shipping'] = false;
            }

            if (!empty($addressData['home_address']) && $addressData['home_address'] === 'true') {
                $extractedCustomerData['home_address'] = $addressId;
                $addressData['home_address'] = true;
            } else {
                $addressData['home_address'] = false;
            }
            $result[] = $addressData;
        }

        return $result;

    }
}