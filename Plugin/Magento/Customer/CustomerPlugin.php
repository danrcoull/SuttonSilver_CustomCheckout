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

		$request = $this->request->getPost();


		$addresses = ( isset( $request->address ) ) ? $request->address : (object)json_decode($request->data);
		if(!isset($addresses->home_address)) {

			$id = 0;
			$homeAddress = false;
			if ( isset($addresses) ) {

				foreach ( $addresses as $key => $val ) {
					if(isset($val['home_address']))
					{
						$homeAddress = true;
						if ( $val['home_address'] == 'true' ) {
							$id = $key;
							break;
						}
					}


				}
			}

			if($homeAddress) {
				$customer->setCustomAttribute( 'home_address', $id );
			}
			//var_dump($id);
			//die(var_dump($customer));
		}
		return [ $customer ];
	}

}