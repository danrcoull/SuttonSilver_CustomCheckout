<?php
namespace SuttonSilver\CustomCheckout\Plugin\Magento\Sales\Api;

use Magento\Framework\Event\ObserverInterface;

class OrderManagementInterface implements ObserverInterface {

	private $customerSession;
	private $checkoutSession;

	public function __construct(
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Checkout\Model\Session $checkoutSession
	) {

		$this->customerSession = $customerSession;
		$this->checkoutSession = $checkoutSession;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		//$this->customerSession->logout();
	}

}