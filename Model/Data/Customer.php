<?php
namespace SuttonSilver\CustomCheckout\Model\Data;

class Customer extends \Magento\Customer\Model\Data\Customer {

    const HOME_ADDRESS = "home_address";

    /**
     * Set default billing address id
     *
     * @param string $homeAddress
     * @return $this
     */
    public function setHomeAddress($homeAddress)
    {
        return $this->setData(self::HOME_ADDRESS, $homeAddress);
    }

    /**
     * @return string|null
     */
    public function getHomeAddress()
    {
        return $this->_get(self::HOME_ADDRESS);
    }
}