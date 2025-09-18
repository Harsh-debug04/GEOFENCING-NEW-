<?php
namespace AgriCart\GeoFencing\Controller\Pincode;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Check extends Action
{
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $pincode = $this->getRequest()->getParam('pincode');
        $geoLocation = $this->getRequest()->getParam('geo_location');

        if (!$pincode || !$geoLocation) {
            return $result->setData(['success' => false, 'message' => __('Invalid request.')]);
        }

        // Normalize line endings and split into an array
        $allowedPincodes = preg_split('/[\r\n]+/', $geoLocation, -1, PREG_SPLIT_NO_EMPTY);

        // Trim whitespace from each pincode
        $allowedPincodes = array_map('trim', $allowedPincodes);

        if (in_array($pincode, $allowedPincodes)) {
            return $result->setData(['success' => true, 'message' => __('Product is available in your location.')]);
        } else {
            return $result->setData(['success' => false, 'message' => __('Product is not available in your location.')]);
        }
    }
}
