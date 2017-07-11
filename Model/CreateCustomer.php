<?php
namespace SuttonSilver\CustomCheckout\Model;
use namespace SuttonSilver\CustomCheckout\Api\CreateCustomerInterface;

class CreateCustomer implements CreateCustomerInterface
{
    /**
     * Returns greeting message to user
     *
     * @api
     * @param string $name Users name.
     * @return string Greeting message with users name.
     */
    public function create(\Magento\Customer\Api\Data\CustomerInterface $customer) {
        die(var_dump($customer->getData()));
    }
}