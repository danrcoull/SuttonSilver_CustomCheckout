<?php
namespace SuttonSilver\CustomCheckout\Plugin\Magento\Sales\Api;

use Magento\Framework\Event\ObserverInterface;

class OrderManagementInterface implements ObserverInterface {

	private $customerSession;

	public function __construct(
		\Magento\Customer\Model\Session $customerSession
	) {

		$this->customerSession = $customerSession;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$this->customerSession->logout();
		return $this;
	}

}