<?php


namespace SuttonSilver\CustomCheckout\Observer;

class CustomcheckoutQuestionSaveBefore implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        die('hello');
        die(var_dump($observer->getObject()->getData()));
    }
}