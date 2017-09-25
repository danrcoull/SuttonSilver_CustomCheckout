<?php
namespace SuttonSilver\CustomCheckout\Plugin\Magento\Sales\Api;

use Magento\Framework\Event\ObserverInterface;

class OrderManagementInterface implements ObserverInterface {

	private $customerSession;
	private $checkoutSession;
	protected $_request;
	protected $_order;
	protected $_logger;
	protected $address_factory;

	public function __construct(
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Framework\App\RequestInterface $request,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Sales\Api\Data\OrderInterface $order,
		\Magento\Customer\Model\AddressFactory $address_factory
	) {

		$this->customerSession = $customerSession;
		$this->checkoutSession = $checkoutSession;
		$this->_order = $order;
		$this->_logger = $logger;
		$this->address_factory = $address_factory;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		//$this->customerSession->logout();
		$orderids = $observer->getEvent()->getOrderIds();
		$post = $this->_request->getPost();
		$this->_logger->info(print_r($post,true));

		foreach($orderids as $orderid){
			$order = $this->_order->load($orderid);
			$billingAddress = $order->getBillingAddress()->getCustomAttribute('dx_number', $post->getData('dx_number'));
			$this->address_factory->create()->updateData($billingAddress)->save();
		}
	}

}