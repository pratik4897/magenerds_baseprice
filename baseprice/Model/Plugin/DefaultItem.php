<?php

namespace Magenerds\BasePrice\Model\Plugin;

use Magento\Quote\Model\Quote\Item;
use Magenerds\BasePrice\Helper\Data;
class DefaultItem
{
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }

    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);
        $product = $item->getProduct();

        $basePriceText = strip_tags($this->helper->getBasePriceText($product));
        $baseData = explode("/",$basePriceText);
        $basePrice = __("entspricht ").$baseData[0].__(" pro ").$baseData[1];
        
        $atts = [
            "product_baseprice" => $basePrice
        ];

        return array_merge($data, $atts);
    }
}