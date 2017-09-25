<?php

namespace SuttonSilver\CustomCheckout\Model\Export;
use Magento\Framework\Filesystem\DriverPool;
use Magento\Framework\File\Csv;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use SuttonSilver\CustomCheckout\Api\QuestionAnswersRepositoryInterface;
use SuttonSilver\CustomCheckout\Api\QuestionRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Psr\Log\LoggerInterface;

abstract class ExportAbstract
{

    const EXPORT_PATH = "var/export";

    protected $driverPool;
    protected $csv;
    protected $OrderCollectionFactory;
    protected $customerRepository;
    protected $questionAnswersRepository;
    protected $questionRepository;
    protected $storeManager;
    protected $searchCriteriaBuilder;
    protected $logger;
	protected $addressRepository;
	protected $checkoutHelper;

	protected $matrixCollectionFactory;
	protected $matrixRepoInterfaceFactory;
	protected $transportBuilder;

    public function __construct( \Magento\Framework\Model\Context $context,
                                DriverPool $driverPool,
                                Csv $csv,
                                OrderCollectionFactory $OrderCollectionFactory,
                                CustomerRepositoryInterface $customerRepository,
                                QuestionAnswersRepositoryInterface $questionAnswersRepository,
                                QuestionRepositoryInterface $questionRepository,
                                StoreManagerInterface $storeManager,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
	                            \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
                                LoggerInterface $logger,
                                \Magento\Checkout\Helper\Data $checkoutHelper,
	    \SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix\CollectionFactory $matrixCollectionFactory,
	    \SuttonSilver\CustomCheckout\Api\MatrixRepositoryInterfaceFactory $matrixRepoInterfaceFactory,
	    \SuttonSilver\CustomCheckout\Mail\Template\TransportBuilder $transportBuilder,
                                array $data = []
    ){
        $this->driverPool = $driverPool;
        $this->csv = $csv;
        $this->OrderCollectionFactory = $OrderCollectionFactory;
        $this->customerRepository = $customerRepository;
        $this->questionAnswersRepository = $questionAnswersRepository;
        $this->questionRepository = $questionRepository;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->logger = $logger;
	    $this->addressRepository = $addressRepository;
	    $this->checkoutHelper = $checkoutHelper;

	    $this->matrixCollectionFactory = $matrixCollectionFactory;
	    $this->matrixRepoInterfaceFactory = $matrixRepoInterfaceFactory;
	    $this->transportBuilder = $transportBuilder;

    }

    public function createExportDir()
    {
	    $this->logger->info('Start creating Export Dir');
        $file =  $this->driverPool->getDriver(DriverPool::FILE);
        if(!$file->isDirectory(self::EXPORT_PATH))
        {
	        $this->logger->info('Creating Export Dir');
            $file->createDirectory(self::EXPORT_PATH, 0755);
        }else {
	        $this->logger->info('Change Export Dir Permissions');
            if(!$file->isWritable(self::EXPORT_PATH)){
                $file->changePermissions(self::EXPORT_PATH,0755);
            }
        }
    }

    public function getCustomerKeys()
    {
        return [
            "ID", "Title", "Forename", "Surname", "DOB", "Date", "House No", "Address1", "Address2", "Address3", "Town", "County", "Postcode",
            "Homephone", "Mobile", "Email", "Membership", "Sponsor", "Employer", "Premises", "Address1", "Address2", "Address3",
            "Town", "County", "Postcode", "Workphone", "DX", "Previous CLS", "Disability", "Read Terms", "Delivery", "Gender Code",
            "Ethnicity Code", "Disability Code", "Prev Title", "Prev Forename", "Prev Surname", "Prev House No", "Prev Address1",
            "Prev Address2", "Prev Address3", "Prev Town", "Prev County", "Prev Postcode",
            "Market", "Reason for study"
        ];
    }

    public function getOrderItemKeys()
    {
        return [
            "Description",
            "Quantity",
            "Price",
            "Shipping",
            "Subtotal",
            "Option"
        ];
    }

    public function saveExport($data)
    {
	    $this->logger->info('Save Export');
        try {
	        $this->logger->info('Creating Export');
            $this->createExportDir();
	        $this->logger->info('Creating Title');
            $file = self::EXPORT_PATH . "/export-" . date('dd-mm-Y') . ".csv";
	        $this->logger->info('Save Export');
            $this->csv->saveData($file, $data);
            $this->sendExport($file);
        }catch(\Exception $e)
        {
	        $this->logger->info('Error:');
            $this->logger->error($e->getMessage());
        }

        return $this;
    }

    public function sendExport($path)
    {
    	try {
		    $transport = $this->transportBuilder->setTemplateIdentifier( 'new_export' )
		                                        ->setTemplateOptions( [ 'area' => 'adminhtml' ] )
		                                        ->setFrom( ['name'=>'CLS Server','email'=>'local@cilexlawschool.co.uk'] )
		                                        ->addTo( ['d.coull@suttonsilver.co.uk','pete@suttonsilver.co.uk'], 'New Export' )
		                                        ->attachFile( $path, $path )
		                                        ->setTemplateVars([])
		                                        ->getTransport();
		    $transport->sendMessage();
	    }catch(\Exception $e)
	    {
		    $this->logger->error('Error:');
		    $this->logger->error($e->getMessage());
	    }
	    return $this;
    }

}