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


class Carrier extends AbstractCarrierOnline implements CarrierInterface
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
        Security $xmlSecurity,
        \Magento\Shipping\Model\Simplexml\ElementFactory $xmlElFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Shipping\Model\Tracking\ResultFactory $trackFactory,
        \Magento\Shipping\Model\Tracking\Result\ErrorFactory $trackErrorFactory,
        \Magento\Shipping\Model\Tracking\Result\StatusFactory $trackStatusFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix\CollectionFactory $matrixCollectionFactory,
        \SuttonSilver\CustomCheckout\Api\MatrixRepositoryInterfaceFactory $matrixRepoInterfaceFactory,
        Config $configHelper,
        array $data = []
    ) {
        $this->_localeFormat = $localeFormat;
        $this->configHelper = $configHelper;

        $this->matrixCollectionFactory = $matrixCollectionFactory;
        $this->matrixRepoInterfaceFactory = $matrixRepoInterfaceFactory;

        parent::__construct(
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $xmlSecurity,
            $xmlElFactory,
            $rateFactory,
            $rateMethodFactory,
            $trackFactory,
            $trackErrorFactory,
            $trackStatusFactory,
            $regionFactory,
            $countryFactory,
            $currencyFactory,
            $directoryData,
            $stockRegistry,
            $data
        );
    }
    protected function _doShipmentRequest(\Magento\Framework\DataObject $request)
    {
    }

    public function getAllowedMethods()
    {
	    return [$this->_code => $this->getConfigData('name')];
    }

    public function collectRates(RateRequest $request)
    {
        $result = $this->_rateFactory->create();
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

		    if (($item->getProduct()->isVirtual() || $item->getProduct()->getTypeId() == 'simple')
		        &&  !$item->getParentItem()) {

			    $product     = $item->getProduct();
			    $matrix      = $this->getMatrixCollection( $product->getSku(), $destination );
			    $arraySkus[] = [ 'sku' => $product->getSku(), 'item' => $item, 'matrix' => $matrix ];
			    $debugArray[] = $item->getProduct()->getSku();
		    }
		}



		foreach ($arraySkus as $item)
		{

			if($item['matrix'] !== null) {
				$associated = $item['matrix']->getAssociatedSkus();
				unset($associated[$item['sku']]);

				foreach ( $associated as $associate ) {
					if ( isset( $arraySkus[ $associate ] ) && $item['sku'] != $associate ) {
						$arraySkus[ $associate ]['custom_price'] = $item['matrix']->getIncrementPrice();
					}
				}

				$singlePrice    = isset( $item['custom_price'] ) ? $item['custom_price'] : $item['matrix']->getSinglePrice();

				$incrementPrice = $item['matrix']->getIncrementPrice();
				if ( $item['item']->getQty() > 1 ) {
					$price = $singlePrice + ( ( $item['item']->getQty() - 1 ) * $incrementPrice );
				} else {
					$price = $singlePrice;
				}


				if ( $price >= $item['matrix']->getMaxPrice() ) {
					$price = $item['matrix']->getMaxPrice();
				}
			}else{
				$price = 0;
			}



			$this->_price += $price;
			$this->_breakdown[$item['sku']] = $price;
		}

		return $this->_price;
    }

    public function getMatrixCollection($sku,$destination)
    {
	    return $this->matrixCollectionFactory->create()
		    ->addFieldToFilter('product_sku', array('eq'=>$sku))
		    ->addFieldToFilter('destination', array('eq'=>$destination))
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