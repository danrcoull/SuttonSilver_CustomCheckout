<?php
namespace Webspeaks\ProductsGrid\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * get products tab Url in admin
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->_backendUrl->getUrl('suttonsilver_customcheckout/question/products', ['_current' => true]);
    }
}