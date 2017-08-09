<?php
namespace SuttonSilver\CustomCheckout\Model;

use Magento\Quote\Model\Quote\Address\RateResult\Error;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrierOnline;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Simplexml\Element;
use Magento\Ups\Helper\Config;
use Magento\Framework\Xml\Security;


class Carrier extends  \Magento\Shipping\Model\Carrier\AbstractCarrier
	implements  \Magento\Shipping\Model\Carrier\CarrierInterface
{

	const CODE = 'clscustomshipping';

    protected $_code = self::CODE;
    protected $_request;
    protected $_result;
    protected $_baseCurrencyRate;
    protected $_xmlAccessRequest;
    protected $_localeFormat;
    protected $_logger;
    protected $configHelper;
    protected $_errors = [];
    protected $_isFixed = true;

    protected $matrixCollectionFactory;
    protected $matrixRepoInterfaceFactory;

    protected $_price = 0;
    protected $_breakdown = [];

	public function __construct(
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
		\Psr\Log\LoggerInterface $logger,
		\Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
		\Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
		\SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix\CollectionFactory $matrixCollectionFactory,
		\SuttonSilver\CustomCheckout\Api\MatrixRepositoryInterfaceFactory $matrixRepoInterfaceFactory,
		array $data = []
	) {
		$this->_rateResultFactory = $rateResultFactory;
		$this->_rateMethodFactory = $rateMethodFactory;

		$this->matrixCollectionFactory = $matrixCollectionFactory;
		$this->matrixRepoInterfaceFactory = $matrixRepoInterfaceFactory;

		parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
	}


    public function getAllowedMethods()
    {
	    return [$this->_code => $this->getConfigData('name')];
    }

    public function collectRates(RateRequest $request)
    {

	    if (!$this->getConfigFlag('active')) {
		    return false;
	    }

        $result = $this->_rateResultFactory->create();
		$price =$this->getPrice($request);


        /*store shipping in session*/
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->getCarrierCode());
        $method->setCarrierTitle($this->getConfigData('title'));

	    $method->setMethod($this->getCarrierCode());
        $method->setMethodTitle($this->getConfigData('name'));
        /* Use method name */

        $method->setCost($price);
        $method->setPrice($price);

        $result->append($method);

        return $result;
    }


    public function getPrice(RateRequest $request)
    {


    	$destination = $request->getDestCountryId() == 'GB' ? 1 : 0;
    	$arraySkus = [];
    	$debugArray = [];
	    foreach ( $request->getAllItems() as $item ) {

		    if (!$item->getParentItem()) {

			    $product     = $item->getProduct();
			    $matrix      = $this->getMatrixCollection( $product->getSku(), $destination );
			    $arraySkus[] = [ 'sku' => $product->getSku(), 'item' => $item, 'matrix' => $matrix ];
			    $debugArray[] = $item->getProduct()->getSku();
		    }
		}

	    foreach ($arraySkus as $item)
	    {
		    $associated = $item['matrix']->getAssociatedSkus();

		    unset($associated[$item['sku']]);

		    foreach ( $associated as $associate ) {
			    if ( isset( $arraySkus[ $associate ] ) && $item['sku'] != $associate ) {
				    $arraySkus[ $associate ]['custom_price'] = $item['matrix']->getIncrementPrice();
			    }
		    }
	    }

		foreach ($arraySkus as $item)
		{

			if($item['matrix']->getId()) {

				$singlePrice    = $item['matrix']->getSinglePrice();
				$incrementPrice = $item['matrix']->getIncrementPrice();


				if ( $item['item']->getQty() > 1 ) {
					$price = $singlePrice + ( ( $item['item']->getQty() - 1 ) * $incrementPrice );
				} else {
					$price = $singlePrice;
				}
				var_dump($singlePrice);
				var_dump($item['matrix']->getMaxPrice());
				if ( $price >= $item['matrix']->getMaxPrice() ) {
					$price = $item['matrix']->getMaxPrice();
				}
				var_dump($price);
			}else{
				$price = 0;
			}



			$this->_price = $this->_price + $price;
			$this->_breakdown[$item['sku']] = $price;
		}
die;
		return $this->_price;
    }

    public function getMatrixCollection($sku,$destination)
    {
    	$sku = explode('-',$sku);
	    return $this->matrixCollectionFactory->create()
		    ->addFieldToFilter('product_sku', $sku[0])
		    ->addFieldToFilter('destination', $destination)
		    ->getFirstItem();
    }

    public function proccessAdditionalValidation(\Magento\Framework\DataObject $request) {
        return true;
    }



	public function isCityRequired()
	{
		return false;
	}
}