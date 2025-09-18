<?php
namespace AgriCart\GeoFencing\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_GEOFENCING = 'geofencing/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GEOFENCING .'general/'. $code, $storeId);
    }

    public function isModuleEnabled($storeId = null)
    {
        return $this->getGeneralConfig('enable', $storeId);
    }

    public function getGoogleApiKey($storeId = null)
    {
        return $this->getGeneralConfig('google_api_key', $storeId);
    }

    public function isMiniMapEnabled($storeId = null)
    {
        return $this->getGeneralConfig('enable_mini_map', $storeId);
    }
}
