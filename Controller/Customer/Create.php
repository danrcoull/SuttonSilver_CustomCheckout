<?php

namespace SuttonSilver\CustomCheckout\Controller\Customer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey;

class Create extends Action
{


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
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
	    \Psr\Log\LoggerInterface $logger,
	    \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        array $data = []

    ){
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonHelper = $jsonHelper;
        $this->formKey = $formKey;
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->questionFactory = $questionFactory;
        $this->questionAnswers = $questionAnswers;
        $this->questionAnswersRepository = $questionAnswersRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->encryptor = $encryptor;
        $this->addressInterface = $addressInterface;
        $this->addressRepositoryInterface = $addressRepositoryInterface;
    }


    public function execute()
    {

    	try {

		    $result   = $this->resultJsonFactory->create();
		    $response = [];
		  //  if ( $this->getRequest()->isAjax() ) {

			    $post = $this->getRequest()->getPost( 'data' );

			    $decodedData = $this->jsonHelper->jsonDecode( $post );


			    if ( $decodedData['form_key'] === $this->formKey->getFormKey() ) {
				    $customerId = $this->createCustomer( $decodedData );
				    if ( $customerId['passed'] ) {
					    $customerId              = $customerId['value'];
					    $response['success']     = true;
					    $response['customer_id'] = $customerId;
					    $ansersResponse          = $this->createCustomAnswers( $decodedData, $customerId );
					    $responseQuote           = $this->setQuote( $customerId );

					    if ( ! $ansersResponse['passed'] ) {
						    unset( $response['success'] );
						    $response['error']    = true;
						    $response['errors'][] = $ansersResponse['value'];
					    }


				    } else {
					    $response['error']    = true;
					    $response['errors'][] = __( $customerId['value'] );
				    }
			    } else {
				    $response['error']    = true;
				    $response['errors'][] = __( 'Form Key Invalid PLease, refresh and try again' );
			    }


			    return $result->setData( $this->jsonHelper->jsonEncode( $response ) );
		   // }
	    }catch(\Exception $e)
	    {
		    $this->logger->critical($e->getMessage());
		    die($e->getMessage());
	    }

        $this->_redirect($this->_redirect->getRefererUrl());
        return false;
    }

    public function createCustomAnswers($data,$id)
    {
        $collection = $this->questionFactory->create();
        $errors= [];
        foreach ($collection as $question)
        {
            $name = strtolower(trim(str_replace(' ','-',$question->getQuestionName())));
            $qid = $question->getId();
            if(array_key_exists($name, $data))
            {
                $searchCriteriaBuilder = $this->searchCriteriaBuilder
                    ->addFilter(
                        'question_id',
                            $qid,
                        'eq'
                    )->addFilter(
                        'customer_id',
                        $id,
                        'eq'
                    )->create();

                $current = $this->questionAnswersRepository->getList($searchCriteriaBuilder);
                $answer = null;
                if($ansers = $current->getItems())
                {
                    foreach($ansers as $answer)
                    {
                        if($aid = $answer->getId())
                        {
                            $answer = $this->questionAnswersRepository->getById($aid);
                        }
                    }
                }

                if(is_null($answer)) {
                    $answer = $this->questionAnswers->create();
                }
                $answer->setQuestionId($qid);
                $answer->setCustomerId($id);
                $answer->setValue($data[$name]);
                try {
                    $this->questionAnswersRepository->save($answer);
                }catch(\Exception $e)
                {
	                $this->logger->critical($e->getMessage());
                    $errors[$name] = __("Could Not Save:".$question->getQuestionName()." With:".$e->getMessage());
                }

            }
        }

        if(count($errors) >= 1)
        {
            return ['passed' => false, 'value' => $errors];
        }else{
            return ['passed' => true];
        }
    }

    public function setQuote($id)
    {
        $customer = $this->customerRepository->getById($id);
        $quote = $this->checkoutSession->getQuote();
        $quote->setCustomerId($customer->getId());
        $quote->setCustomer($customer);
        $quote->setCustomerFirstname($customer->getFirstname());
        $quote->setCustomerLastname($customer->getLastname());
        $quote->setIsChanged(1);
        try {
	        $this->quoteRepository->save( $quote );
        }catch(\Exception $e)
        {
	        $this->logger->critical($e->getMessage());
	        return ['passed' => false, 'value' => $e->getMessage()];
        }

        return true;
    }

    public function createCustomer($data)
    {

        // Get Website ID
	    $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();


        //check if the customer exists if not create new.
        try{

            $customer = $this->customerRepository->get($data['username'],$websiteId);
            //$customer = $this->customerFactory->create()->loadByEmail($data['username']);

        }catch(\Magento\Framework\Exception\NoSuchEntityException $e)
        {
	        $customer = $this->customerFactory;

        }catch(\Exception $e)
        {
	        $this->logger->critical($e->getMessage());
	        return ['passed' => false, 'value' => $e->getMessage()];
        }
        //set the website (multi site usuage)


        $customer->setWebsiteId($websiteId);

	    $storeId = $this->storeManager->getWebsite($websiteId)->getDefaultStore()->getId();
	    $customer->setStoreId($storeId);

        $customer->setEmail(isset($data['username']) ? $data['username'] : '');
        $customer->setPrefix(isset($data['title']) ? $data['title'] : "");
        $customer->setFirstname(isset($data['firstname']) ? $data['firstname'] : "");
        $customer->setLastname(isset($data['lastname']) ? $data['lastname'] : "");

	    $storeName = $this->storeManager->getStore($customer->getStoreId())->getName();
	    $customer->setCreatedIn($storeName);

        $customer->setCustomAttribute('cilex_membership_number',isset($data['cilex_membership_number']) ? $data['cilex_membership_number'] : "");
        $customer->setCustomAttribute('previous_surname',isset($data['previous_surname']) ? $data['previous_surname'] : "");
        $customer->setCustomAttribute('previous_postcode',isset($data['previous_postcode']) ? $data['previous_postcode'] : "");

        $customer->setCustomAttribute('studied_with_us_before', isset($data['have_studied']) ? $data['have_studied'] : false );
        $customer->setCustomAttribute('daytime_phone_number',isset($data['daytimeNumber']) ? $data['daytimeNumber'] : "");
        $customer->setCustomAttribute('mobile_number',isset($data['mobileNumber']) ? $data['mobileNumber'] : "");

	    $customer->setCustomAttribute('is_read_only',true);

        try {
	        $customer = $this->customerRepository->save( $customer );
	        //die( 'hello i am here' );


	        $address = $this->addressInterface
		        ->setCustomerId( $customer->getId() )
		        ->setFirstname( $customer->getFirstname() )
		        ->setLastname( $customer->getLastname() )
		        ->setCountryId( isset( $data['country_id'] ) ? $data['country_id'] : '' )
		        ->setPostcode( isset( $data['postcode'] ) ? $data['postcode'] : '' )
		        ->setCity( isset( $data['city'] ) ? $data['city'] : '' )
		        ->setTelephone( isset( $data['daytimeNumber'] ) ? $data['daytimeNumber'] : '' )
		        ->setStreet( isset( $data['street'] ) ? $data['street'] : '' )
		        ->setCustomAttribute( 'home_address', 'true' )
		        ->setIsDefaultShipping( '1' )
		        ->setSaveInAddressBook( '1' );
	        $this->addressRepositoryInterface->save( $address );

	        //$customer->save();
        }catch(\Exception $e)
        {
	        $this->logger->critical($e->getMessage());
            return ['passed' => false, 'value' => $e->getMessage()];
        }

        return ['passed' => true, 'value' =>$customer->getId()];

    }
}