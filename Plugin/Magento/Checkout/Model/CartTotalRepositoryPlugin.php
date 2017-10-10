<?php

namespace SuttonSilver\CustomCheckout\Plugin\Magento\Checkout\Model;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Cart\CartTotalRepository;


/**
 * Class SampleConfigProvider
 */
class CartTotalRepositoryPlugin
{

    protected  $session;

    public function __construct( Session $session,
                                 \Magento\Framework\Model\Context $context,
                                 \Magento\Framework\Registry $registry,
                                 array $data = []){

        $this->session = $session;

    }
    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function afterGet(CartTotalRepository $subject, array $result)
    {

        $discount = 0;
        $items = $this->session->getQuote()->getAllVisibleItems();
        foreach($items as $item)
        {
            $itemCost = $item->getProduct()->getPrice();
            $childCost = 0;
            foreach($item->getChildren() as $child) {
                //var_dump($child->getProduct()->getPrice());
                $childCost += $child->getProduct()->getPrice();
            }
            //var_dump($childCost);
            $discount += ($childCost -$itemCost);
        }


        $result['totalsData']['discount_amount'] = number_format(ceil($discount * 1.2), 2);
        return $result;
    }
}