<?php

namespace SuttonSilver\CustomCheckout\Model;

use SuttonSilver\CustomCheckout\Api\Data\MatrixInterface;

class Matrix extends \Magento\Framework\Model\AbstractModel implements MatrixInterface
{

	/**
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('SuttonSilver\CustomCheckout\Model\ResourceModel\Matrix');
	}

	public function getMatrixId() {
		return $this->getData(self::MATRIX_ID);
	}

	public function setMatrixId( $matrixId ) {
		$this->setData(self::MATRIX_ID,$matrixId);
		return $this;
	}

	public function getProductSku() {
		return $this->getData(self::PRODUCT_SKU);
	}

	public function setProductSku( $productSku ) {
		$this->setData(self::PRODUCT_SKU,$productSku);
		return $this;
	}

	public function getDestination() {
		return $this->getData(self::DESTINATION);
	}

	public function setDestination( $destination ) {
		$this->setData(self::DESTINATION, $destination);
		return $this;
	}

	public function getSinglePrice() {
		return $this->getData(self::SINGLE_PRICE);
	}

	public function setSinglePrice( $singlePrice ) {
		$this->setData(self::SINGLE_PRICE, $singlePrice);
		return $this;
	}

	public function getIncrementPrice() {
		return $this->getData(self::INCREMENT_PRICE);
	}

	public function setIncrementPrice( $incrementPrice ) {
		$this->setData(self::INCREMENT_PRICE, $incrementPrice);
		return $this;
	}

	public function getMaxPrice() {
		return $this->getData(self::MAX_PRICE);
	}

	public function setMaxPrice( $maxPrice ) {
		$this->setData(self::MAX_PRICE, $maxPrice);
		return $this;
	}

	public function getNoOverseas() {
		return $this->getData(self::NO_OVERSEAS);
	}

	public function setNoOverseas( $noOverseas ) {
		$this->setData(self::NO_OVERSEAS, $noOverseas);
		return $this;
	}

	public function getAssociatedSkus() {
		$associatedSkus = $this->getData(self::ASSOCIATED_SKUS);
		$associatedSkus = (is_array($associatedSkus) ? $associatedSkus : explode(',', $associatedSkus));
		return $associatedSkus;
	}

	public function setAssociatedSkus( $associatedSkus ) {
		$associatedSkus = (is_array($associatedSkus) ? $associatedSkus : implode(',', $associatedSkus));
		$this->setData(self::ASSOCIATED_SKUS, $associatedSkus);
		return $this;
	}


}