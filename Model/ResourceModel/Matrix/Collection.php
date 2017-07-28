<?php


namespace SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init(
			'SuttonSilver\CustomCheckout\Model\Matrix',
			'SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix'
		);
	}
}
