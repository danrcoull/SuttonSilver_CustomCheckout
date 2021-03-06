<?php

namespace SuttonSilver\CustomCheckout\Controller\Customer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey;

class Logout extends Action {


	protected $resultJsonFactory;
	protected $jsonHelper;
	protected $resultPageFactory;
	protected $formKey;
	protected $storeManager;
	protected $customerFactory;
	protected $customerRepository;
	protected $questionFactory;
	protected $questionAnswers;
	protected $questionAnswersRepository;
	protected $searchCriteriaBuilder;
	protected $checkoutSession;
	protected $customerSession;
	protected $quoteRepository;
	protected $logger;
	protected $encryptor;
	protected $addressInterface;
	protected $addressRepositoryInterface;
	protected $region_interface_factory;
	protected $customerModelFactory;
	protected $configProvider;


	public function __construct(
		Context $context,
		JsonFactory $resultJsonFactory,
		PageFactory $resultPageFactory,
		Data $jsonHelper,
		FormKey $formKey,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Customer\Api\Data\CustomerInterface $customerFactory,
		\Magento\Customer\Api\Data\AddressInterface $addressInterface,
		\Magento\Customer\Api\AddressRepositoryInterface $addressRepositoryInterface,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
		\SuttonSilver\CustomCheckout\Model\ResourceModel\Question\CollectionFactory $questionFactory,
		\SuttonSilver\CustomCheckout\Model\QuestionAnswersFactory $questionAnswers,
		\SuttonSilver\CustomCheckout\Api\QuestionAnswersRepositoryInterface $questionAnswersRepository,
		\Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Customer\Model\CustomerFactory $customerModelFactory,
		\Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Encryption\EncryptorInterface $encryptor,
		\Magento\Customer\Api\Data\RegionInterfaceFactory $region_interface_factory,
		\Magento\Checkout\Model\CompositeConfigProvider $configProvider,
		array $data = []

	) {
		parent::__construct( $context );
		$this->resultPageFactory          = $resultPageFactory;
		$this->resultJsonFactory          = $resultJsonFactory;
		$this->jsonHelper                 = $jsonHelper;
		$this->formKey                    = $formKey;
		$this->storeManager               = $storeManager;
		$this->customerFactory            = $customerFactory;
		$this->customerRepository         = $customerRepository;
		$this->questionFactory            = $questionFactory;
		$this->questionAnswers            = $questionAnswers;
		$this->questionAnswersRepository  = $questionAnswersRepository;
		$this->searchCriteriaBuilder      = $searchCriteriaBuilder;
		$this->checkoutSession            = $checkoutSession;
		$this->customerSession            = $customerSession;
		$this->quoteRepository            = $quoteRepository;
		$this->logger                     = $logger;
		$this->encryptor                  = $encryptor;
		$this->addressInterface           = $addressInterface;
		$this->addressRepositoryInterface = $addressRepositoryInterface;
		$this->region_interface_factory   = $region_interface_factory;
		$this->customerModelFactory       = $customerModelFactory;
		$this->configProvider             = $configProvider;
	}


	public function execute() {
		$result = $this->resultJsonFactory->create();

		try {
			$response = [];
			if ( $this->getRequest()->isAjax() ) {
				$this->customerSession->logout();
			}
		} catch ( \Exception $e ) {

			$response = [ 'error' => 'true', 'message' => $e->getMessage() ];

		}

		return $result->setData( $response );
	}
}