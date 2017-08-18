<?php
namespace SuttonSilver\CustomCheckout\Plugin\Magento\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;
use Magento\Customer\Api\Data\CustomerInterface;

class CustomerPlugin
{
	protected $request;

	public function __construct(
		\Magento\Framework\App\Request\Http $request
    ) {
       $this->request = $request;
    }

	public function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}


	public function beforeSave(CustomerRepository $subject, CustomerInterface $customer) {

		$addresses = $this->request->getPost( 'address' );
		$addresses = ($this->isJson($addresses)) ? $addresses : json_decode($addresses);
		$id        = 0;
		if ( $addresses ) {

			foreach ( $addresses as $key => $val ) {
				var_dump($val);
				if ( $val['home_address'] == 'true' ) {
					$id = $key;
					break;
				}
			}
		}else {
			$id = $customer->getCustomAttribute('home_address');
		}

		$customer->setCustomAttribute('home_address',$id);
		die;
		return [$customer];
	}

}