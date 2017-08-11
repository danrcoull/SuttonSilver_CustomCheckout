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



	public function beforeSave(CustomerRepository $subject, CustomerInterface $customer)
	{
		$addresses = $this->request->getPost('address');
		$id = 0;
		foreach ($addresses as $key => $val)
		{

			if($val['home_address'] == 'true')
			{
				$id = $key;
				break;
			}
		}

		$customer->setCustomAttribute('home_address',$id);

		return [$customer];
	}

}