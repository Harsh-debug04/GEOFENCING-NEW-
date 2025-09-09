<?php
namespace AgriCart\GeoFencing\Controller\Pincode;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\Client\Curl;

class Check extends Action
{
    protected $resultJsonFactory;
    protected $curl;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Curl $curl
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->curl = $curl;
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

        try {
            $this->curl->get('https://api.postalpincode.in/pincode/' . $pincode);
            $response = json_decode($this->curl->getBody(), true);

            if ($response && isset($response[0]['Status']) && $response[0]['Status'] == 'Success') {
                $postOffice = $response[0]['PostOffice'][0];
                $locationName = strtolower($postOffice['Name']);
                $locationDistrict = strtolower($postOffice['District']);
                $locationState = strtolower($postOffice['State']);

                if (stripos(strtolower($geoLocation), $locationName) !== false ||
                    stripos(strtolower($geoLocation), $locationDistrict) !== false ||
                    stripos(strtolower($geoLocation), $locationState) !== false) {
                    return $result->setData(['success' => true, 'message' => __('Product is available in your location.')]);
                } else {
                    return $result->setData(['success' => false, 'message' => __('Product is not available in your location.')]);
                }
            } else {
                return $result->setData(['success' => false, 'message' => __('Could not verify the pincode.')]);
            }
        } catch (\Exception $e) {
            return $result->setData(['success' => false, 'message' => __('An error occurred while checking the pincode.')]);
        }
    }
}
