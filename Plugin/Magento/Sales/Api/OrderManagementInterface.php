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
		\Magento\Sales\Model\Order $order,
		\Magento\Customer\Model\AddressFactory $address_factory
	) {

		$this->customerSession = $customerSession;
		$this->checkoutSession = $checkoutSession;
		$this->_order = $order;
		$this->_logger = $logger;
		$this->address_factory = $address_factory;
		$this->_request = $request;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		//$this->customerSession->logout();
		$orderids = $observer->getEvent()->getOrderIds();
		$post = $this->_request->getParams();
		$this->_logger->info(print_r($post,true));

		foreach($orderids as $orderid){
			$order = $this->_order->load($orderid);
			$billingAddress = $order->getBillingAddress()->getId();
			$this->address_factory->create()->load($billingAddress)->setData('dx_number', $post['dx_number'])->save();
		}
	}

}