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

		$addresses = $customer->getAddresses();
		$id        = 0;
		if ( $addresses ) {

			foreach ( $addresses as $key => $val ) {
				var_dump($val->getId());

				if ( $val['home_address'] == 'true' ) {
					$id = $key;
					break;
				}
			}
		}else {
			$id = $customer->getCustomAttribute('home_address');
		}

		die;
		$customer->setCustomAttribute('home_address',$id);

		return [$customer];
	}

}