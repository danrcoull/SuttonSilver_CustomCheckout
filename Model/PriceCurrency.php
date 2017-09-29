<?php
namespace SuttonSilver\CustomCheckout\Model;
class PriceCurrency extends \Magento\Directory\Model\PriceCurrency
{
	const DEFAULT_PRECISION = 4;
	public function round($price)
	{
		return round($price, self::DEFAULT_PRECISION);
	}
	public function convertAndRound($amount, $scope = null, $currency = null, $precision = self::DEFAULT_PRECISION)
	{
		return parent::convertAndRound($amount, $scope, $currency, $precision);
	}
}