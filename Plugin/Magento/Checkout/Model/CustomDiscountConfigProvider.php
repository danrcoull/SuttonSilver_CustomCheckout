<?php

namespace SuttonSilver\CustomCheckout\Plugin\Magento\Checkout\Model;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Checkout\Model\Session;


/**
 * Class SampleConfigProvider
 */
class CustomDiscountConfigProvider extends \Magento\Framework\Model\AbstractModel
{

    protected  $session;

    public function __construct( Session $session,
                                 \Magento\Framework\Model\Context $context,
                                 \Magento\Framework\Registry $registry,
                                 array $data = []){

        parent::__construct($context,$registry,null,null, $data);

        $this->session = $session;

    }
    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function afterGetConfig(DefaultConfigProvider $subject, array $result)
    {

        $discount = 0;
        $items = $this->session->getQuote()->getAllVisibleItems();
        $array = $result['totalsData']['items'];
        foreach($items as $key => $item)
        {

            $itemCost = $item->getProduct()->getPrice();
            $childCost = 0;
            foreach($item->getChildren() as $child) {
                $childCost += $child->getProduct()->getPrice();
            }

            $discount += $childCost > 0 ? ($itemCost-$childCost) : 0;
            $array[$key]['discount'] = $childCost > 0 ? ($itemCost-$childCost) : 0;
        }

        $result['totalsData']['discount_amount'] = number_format($discount, 4);
        $result['totalsData']['items'] = $array;
        return $result;
    }
}