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


	public function beforeSave(CustomerRepository $subject, CustomerInterface $customer) {

		$request = $this->request->getPost( 'address' );
		$addresses = (json_decode($request) !== FALSE ) ?  json_decode($request) : $request;
		$id = 0;
		if ( $addresses ) {

			foreach ( $addresses as $key => $val ) {
				var_dump($val);
				if ( $val['home_address'] == 'true' ) {
					$id = $key;
					break;
				}
			}
		}

		$customer->setCustomAttribute('home_address',$id);

		return [$customer];
	}

}