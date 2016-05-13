<?php namespace Meanbee\Royalmail;

class CalculateMethod
{
    public $_csvCountryCode;
    public $_csvZoneToDeliveryMethod;
    public $_csvDeliveryMethodMeta;
    public $_csvDeliveryToPrice;
    public $_csvCleanNameToMethod;
    public $_csvCleanNameMethodGroup;

    public function __construct($csvCountryCode = null, $csvZoneToDeliveryMethod = null, $csvDeliveryMethodMeta = null,
                                $csvDeliveryToPrice = null, $csvCleanNameToMethod = null,
                                $csvCleanNameMethodGroup = null)
    {
        $dir = dirname(realpath(__FILE__)) . '/';

        // Set the default csv values
        $this->_csvCountryCode = isset($csvCountryCode) ? $csvCountryCode : $dir . '../data/1_countryToZone.csv';
        $this->_csvZoneToDeliveryMethod = isset($csvZoneToDeliveryMethod) ? $csvZoneToDeliveryMethod :
            $dir . '../data/2_zoneToDeliveryMethod.csv';
        $this->_csvDeliveryMethodMeta = isset($csvDeliveryMethodMeta) ? $csvDeliveryMethodMeta :
            $dir . '../data/3_deliveryMethodMeta.csv';
        $this->_csvDeliveryToPrice = isset($csvDeliveryToPrice) ? $csvDeliveryToPrice :
            $dir . '../data/4_deliveryToPrice.csv';
        $this->_csvCleanNameToMethod = isset($csvCleanNameToMethod) ? $csvCleanNameToMethod :
            $dir . '../data/5_cleanNameToMethod.csv';
        $this->_csvCleanNameMethodGroup = isset($csvCleanNameMethodGroup) ? $csvCleanNameMethodGroup :
            $dir . '../data/6_cleanNameMethodGroup.csv';
    }

    /**
     * Method to run the appropriate sorting methods
     * in the correct order based on the country code,
     * package value, and package weight. Returns the
     * sorted values to the RoyalMailMethod class to be
     * converted into objects.
     *
     * @param $country_code
     * @param $package_value
     * @param $package_weight
     *
     * @return array
     */
    public function getMethods($country_code, $package_value, $package_weight)
    {
        $data = new Data(
            $this->_csvCountryCode,
            $this->_csvZoneToDeliveryMethod,
            $this->_csvDeliveryMethodMeta,
            $this->_csvDeliveryToPrice,
            $this->_csvCleanNameToMethod,
            $this->_csvCleanNameMethodGroup
        );

        $sortedDeliveryMethods = [$data->calculateMethods($country_code, $package_value, $package_weight)];

        $results = [];

        foreach ($sortedDeliveryMethods as $shippingMethod) {
            foreach ($shippingMethod as $item) {
                $method = new Method();
                $method->countryCode = $country_code;
                $method->shippingMethodName = $item['shippingMethodName'];
                $method->minimumWeight = $item['minimumWeight'];
                $method->maximumWeight = $item['maximumWeight'];
                $method->methodPrice = $item['methodPrice'];
                $method->insuranceValue = $item['insuranceValue'];
                $method->shippingMethodNameClean = $item['shippingMethodNameClean'];

                if (isset($item['size'])) {
                    $method->size = $item['size'];
                }

                $results[] = $method;
            }
        }
        return $results;
    }
}
