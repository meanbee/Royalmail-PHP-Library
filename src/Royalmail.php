<?php namespace Meanbee;

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@meanbee.com so we can send you a copy immediately.
 *
 * @category   Meanbee
 * @package    Meanbee_Royalmail
 * @copyright  Copyright (c) 2008 Meanbee Internet Solutions (http://www.meanbee.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once("data.php");

class RoyalMail
{

    // Array to temporarily hold the sorted country code methods
    private $sortedCountryCodeMethods = array();

    // Array to temporarily hold the sorted world zone to methods
    private $sortedZoneToMethods = array();

    // Array to temporarily hold the sorted method meta data
    private $sortedMethodToMeta = array();

    // Array to temporarily hold the sorted methods
    private $sortedDeliveryToPrices = array();

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
        $data = new data();

        $this->sortedCountryCodeMethods = array(
            $this->getCountryCodeData(
                $country_code,
                $data->mappingCountryToZone
            )
        );

        $this->sortedZoneToMethods = array(
            $this->getZoneToMethod(
                $this->sortedCountryCodeMethods,
                $data->mappingZoneToMethod
            )
        );

        $this->sortedMethodToMeta = array(
            $this->getMethodToMeta(
                $package_value,
                $this->sortedZoneToMethods,
                $data->mappingMethodToMeta
            )
        );

        $this->sortedDeliveryToPrices = array(
            $this->getMethodToPrice(
                $package_weight,
                $this->sortedMethodToMeta,
                $data->mappingDeliveryToPrice
            )
        );

        return $this->sortedDeliveryToPrices;
    }

    /**
     *
     * Method to return a 2d array of world zones a country
     * (by its country code) is located in.
     *
     * @param $country_code
     * @param $mappingCountryToZone
     *
     * @return array
     */
    private function getCountryCodeData($country_code, $mappingCountryToZone)
    {
        // Get All array items that match the country code
        $countryCodeData = array();
        foreach ($mappingCountryToZone as $item) {
            if (isset($item[0]) && $item[0] == $country_code) {
                foreach ($item as $keys) {
                    $countryCodeData[] = $keys;
                }
            }
        }

        // Clean up the array removing excess values
        foreach ($countryCodeData as $key => $value) {
            if ($value == $country_code) {
                unset($countryCodeData[$key]);
            }
        }

        $countryCodeData = array_values($countryCodeData);

        return $countryCodeData;
    }

    /**
     * Method to return a 2d array of possible delivery methods based
     * on the given world zones a country is in.
     *
     * @param $sortedCountryCodeMethods
     * @param $mappingZoneToMethod
     *
     * @return array
     */
    private function getZoneToMethod($sortedCountryCodeMethods, $mappingZoneToMethod)
    {
        $mappingZoneData = array();
        foreach ($sortedCountryCodeMethods as $key => $value) {
            foreach ($value as $zone) {
                foreach ($mappingZoneToMethod as $item) {
                    if (isset($item[0]) && $item[0] == $zone) {
                        foreach ($item as $keys) {
                            $mappingZoneData[] = $keys;
                        }

                    }
                }
            }
        }

        // Clean up the array removing excess values
        foreach ($sortedCountryCodeMethods as $item => $itemValue) {
            foreach ($itemValue as $zone) {
                foreach ($mappingZoneData as $key => $value) {
                    if ($value == $zone) {
                        unset($mappingZoneData[$key]);
                    }
                }
            }

        }

        $mappingZoneData = array_values($mappingZoneData);

        return $mappingZoneData;

    }

    /**
     * Method to return a 2d array of the meta data for each
     * given allowed shipping method and the given package
     * value.
     *
     * @param $packageValue
     * @param $sortedZoneToMethods
     * @param $mappingMethodToMeta
     *
     * @return array
     */
    private function getMethodToMeta($packageValue, $sortedZoneToMethods, $mappingMethodToMeta)
    {
        $mappingZoneMethodData = array();
        foreach ($sortedZoneToMethods as $key => $value) {
            foreach ($value as $method) {
                foreach ($mappingMethodToMeta as $item) {
                    if (isset($item[0]) && $item[0] == $method) {
                        if ($packageValue >= $item[1] && $packageValue <= $item[2]) {
                            foreach ($item as $keys) {
                                $mappingZoneMethodData[] = $keys;
                            }
                        }

                    }
                }
            }
        }

        $mappingZoneMethodData = array_values($mappingZoneMethodData);

        return $mappingZoneMethodData;

    }

    /**
     * Method to return a 2d array of sorted shipping methods based on
     * the weight of the item and the allowed shipping methods. Returns
     * a 2d array to be converting into objects by the RoyalMailMethod
     * class.
     *
     * @param $package_weight
     * @param $sortedMethodToMeta
     * @param $mappingDeliveryToPrice
     *
     * @return array
     */
    private function getMethodToPrice($package_weight, $sortedMethodToMeta, $mappingDeliveryToPrice)
    {
        $mappingDeliveryToPriceData = array();
        foreach ($sortedMethodToMeta as $key => $value) {
            foreach ($value as $method) {
                foreach ($mappingDeliveryToPrice as $item) {
                    if (isset($item[0]) && $item[0] == $method) {
                        if ($package_weight >= $item[1] && $package_weight <= $item[2]) {
                            $mappingDeliveryToPriceData[] = array(
                                'shippingMethodName'      => $item[0],
                                'minimumWeight'           => $item[1],
                                'maximumWeight'           => $item[2],
                                'methodPrice'             => $item[3],
                                'insuranceValue'          => $item[4],
                                'shippingMethodNameClean' => $item[5]
                            );
                        }
                    }
                }
            }
        }

        $mappingDeliveryToPriceData = array_values($mappingDeliveryToPriceData);

        return $mappingDeliveryToPriceData;

    }
}
