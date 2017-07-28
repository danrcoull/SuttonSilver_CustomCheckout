<?php


namespace SuttonSilver\CustomCheckout\Model\ResourceModel;

class Matrix extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('suttonsilver_cls_matrix', 'cls_matrix_id');
	}
}
