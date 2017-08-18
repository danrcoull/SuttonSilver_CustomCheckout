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

		//$addresses = $this->request->getPost( 'address' );
		$addresses = $customer->getAddresses();
		$id        = 0;
		if ( $addresses ) {

			foreach ( $addresses as $address ) {
				print_r($address->getCustomAttributes());

			}
		}
		die;
		$customer->setCustomAttribute('home_address',$id);

		return [$customer];
	}

}