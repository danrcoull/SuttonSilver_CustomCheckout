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
		\Magento\Sales\Api\Data\OrderAddressInterface $address_factory
	) {

		$this->customerSession = $customerSession;
		$this->checkoutSession = $checkoutSession;
		$this->_order = $order;
		$this->_logger = $logger;
		$this->address_factory = $address_factory;
		$this->_request = $request;
	}

	public function execute(\Magento\Framework\Event\Observer $observer) {

		$post = $this->_request->getPost();
		$this->_logger->info( print_r( $post, true ) );
		$order          = $this->checkoutSession->getLastRealOrder();
		$billingAddress = $order->getShippigAddressId();
		if(isset($post['dx_number'] )) {
			$productExtension = $order->getShippingAddress()->getExtensionAttributes();
			$productExtension->setDxNumber($post['dx_number']);
			$address = $order->getShippingAddress()->setExtensionAttributes($productExtension);

			$order->setShippingAddress($address)->save();

		}
	}

}