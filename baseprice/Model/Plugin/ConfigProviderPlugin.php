<?php

namespace Magenerds\BasePrice\Model\Plugin;

use Magento\Checkout\Model\DefaultConfigProvider;
use Magento\Framework\Model\AbstractModel;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Model\Product;
use Magenerds\BasePrice\Helper\Data;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class ConfigProviderPlugin extends AbstractModel
{
    public function __construct(
        Item $item,
        Product $product,
        Data $helper,
        Context $context,
        Registry $registry,
        PriceHelper $priceHelper,
        array $data = []
    ) {
        $this->item = $item;
        $this->product = $product;
        $this->helper = $helper;
        $this->priceHelper = $priceHelper;
        parent::__construct($context, $registry);
    }

    public function afterGetConfig(DefaultConfigProvider $subject, array $result)
    {
        $items = $result['totalsData']['items'];
        foreach($items as $index => $item){
            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $checkoutSession = $_objectManager->get('Magento\Checkout\Model\Session');
            $quoteItem = $checkoutSession->getQuote()->getItemById($item['item_id']);
            $basePriceText = strip_tags($this->helper->getBasePriceText($quoteItem->getProduct()));
            $data = explode("/",$basePriceText);
            $basePrice = __("entspricht ").$data[0].__(" pro ").$data[1];
            $items[$index]['baseprice'] = $basePrice;
        }
        $result['totalsData']['items'] = $items;
        return $result;
    }
}