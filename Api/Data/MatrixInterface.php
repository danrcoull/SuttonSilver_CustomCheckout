<?php


namespace SuttonSilver\CustomCheckout\Api\Data;

interface MatrixInterface
{
	const MATRIX_ID = 'cls_matrix_id';
	const PRODUCT_SKU = 'product_sku';
	const DESTINATION = 'destination';
	const SINGLE_PRICE = 'single_price';
	const INCREMENT_PRICE = 'increment_price';
	const MAX_PRICE = 'max_price';
	const NO_OVERSEAS = 'no_overseas';
	const ASSOCIATED_SKUS = 'associated_skus';


	public function getMatrixId();
	public function setMatrixId($matrixId);

	public function getProductSku();
	public function setProductSku($productSku);

	public function getDestination();
	public function setDestination($destination);

	public function getSinglePrice();
	public function setSinglePrice($singlePrice);

	public function getIncrementPrice();
	public function setIncrementPrice($incrementPrice);

	public function getMaxPrice();
	public function setMaxPrice($maxPrice);

	public function getNoOverseas();
	public function setNoOverseas($noOverseas);

	public function getAssociatedSkus();
	public function setAssociatedSkus($associatedSkus);

}