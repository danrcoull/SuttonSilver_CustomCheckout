<?php
namespace SuttonSilver\CustomCheckout\Plugin\Magento\Sales\Api;
class OrderManagementInterface {

	private $customerSession;

	public function __construct(
		\Magento\Customer\Model\Session $customerSession
	) {

		$this->customerSession = $customerSession;
	}

	public function afterPlace(\Magento\Sales\Api\Data\OrderInterface $subject)
	{
		$this->customerSession->logout();
		return $subject;
	}

}