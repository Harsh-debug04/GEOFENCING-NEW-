<?php
namespace AgriCart\GeoFencing\Block\Product;

use Magento\Catalog\Block\Product\View as ProductView;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use AgriCart\GeoFencing\Helper\Data as GeoFencingHelper;

class View extends ProductView
{
    protected $_helper;

    public function __construct(
        Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        GeoFencingHelper $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    public function getGeoLocation()
    {
        return $this->getProduct()->getGeoLocation();
    }

    public function getGoogleApiKey()
    {
        return $this->_helper->getGoogleApiKey();
    }

    public function isModuleEnabled()
    {
        return $this->_helper->isModuleEnabled();
    }

    public function isMiniMapEnabled()
    {
        return $this->_helper->isMiniMapEnabled();
    }
}
