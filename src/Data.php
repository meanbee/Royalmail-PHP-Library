<?php
/**
 * Meanbee Royal Mail PHP Library
 *
 * PHP version 5.6
 *
 * @category  Meanbee
 * @package   Meanbee/royalmail-php-library
 * @author    Meanbee Limited <hello@meanbee.com>
 * @copyright 2016 Meanbee Limited (http://www.meanbee.com)
 * @license   OSL v. 3.0
 * @link      http://github.com/meanbee/royalmail-php-library
 */

namespace Meanbee\Royalmail;

/**
 * Class Data
 * Data layer class. Interacts with the csv files and creates sorted mapped arrays
 * out of them. Provides methods to interact with the csv files and return sorted
 * method rates of several formats.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class Data
{

    // Constants to link to the appropriate columns in the CSV files
    const COUNTRY_CODE = 0;
    const WORLD_ZONE = 0;
    const METHOD_ID_COLUMN = 0;
    const METHOD_MIN_VALUE = 1;
    const METHOD_MAX_VALUE = 2;
    const MIN_WEIGHT_COLUMN = 1;
    const MAX_WEIGHT_COLUMN = 2;
    const PRICE_COLUMN = 3;
    const INSURANCE_VALUE_COLUMN = 4;
    const NAME_COLUMN = 4;
    const SIZE_COLUMN = 5;
    const CODE_COLUMN = 5;
    const COUNTRY_ZONE_COLUMN = 1;
    const METHOD_COLUMN = 1;
    /**
     * Maps countries to zones.
     *
     * @var array
     */
    protected $mappingCountryToZone = [];

    /**
     * Maps zones to methods.
     *
     * @var array
     */
    protected $mappingZoneToMethod = [];

    /**
     * Map methods to meta information. This includes the insurance
     * amount, and the corresponding price levels
     *
     * @var array
     */
    protected $mappingMethodToMeta = [];

    /**
     * Maps method to prices (rates) based on weight boundaries
     *
     * @var array
     */
    protected $mappingDeliveryToPrice = [];

    /**
     * Data constructor.
     *
     * @param string $csvCountryCode          - country code csv path
     * @param string $csvZoneToDeliveryMethod - zone to method csv path
     * @param string $csvDeliveryMethodMeta   - delivery method meta csv path
     * @param string $csvDeliveryToPrice      - delivery to price csv path
     */
    public function __construct(
        $csvCountryCode = null,
        $csvZoneToDeliveryMethod = null,
        $csvDeliveryMethodMeta = null,
        $csvDeliveryToPrice = null
    ) {
        $dir = dirname(realpath(__FILE__)) . '/';

        // Set the default csv values
        if (is_null($csvCountryCode)) {
            $csvCountryCode = "$dir../data/1_countryToZone.csv";
        }

        if (is_null($csvZoneToDeliveryMethod)) {
            $csvZoneToDeliveryMethod = "$dir../data/2_zoneToDeliveryMethod.csv";
        }

        if (is_null($csvDeliveryMethodMeta)) {
            $csvDeliveryMethodMeta = "$dir../data/3_deliveryMethodMeta.csv";
        }

        if (is_null($csvDeliveryToPrice)) {
            $csvDeliveryToPrice = "$dir../data/4_deliveryToPrice.csv";
        }

        $this->mappingCountryToZone = $this->_csvToArray($csvCountryCode);
        $this->mappingZoneToMethod = $this->_csvToArray($csvZoneToDeliveryMethod);
        $this->mappingMethodToMeta = $this->_csvToArray($csvDeliveryMethodMeta);
        $this->mappingDeliveryToPrice = $this->_csvToArray($csvDeliveryToPrice);
    }

    /**
     * Method to run the appropriate sorting methods
     * in the correct order based on the country code,
     * package value, and package weight. Returns the
     * sorted values to the RoyalMailMethod class to be
     * converted into objects.
     *
     * The $ignore_package_value parameter allows for the
     * value of the packages to be ignored in the calculation
     * at the users discretion.
     *
     * @param string $countryCode        - Country code being shipped to
     * @param int    $packageValue       - Package value
     * @param int    $packageWeight      - Package weight
     * @param bool   $ignorePackageValue - ignore value bool
     *
     * @return array
     */
    public function calculateMethods(
        $countryCode,
        $packageValue,
        $packageWeight,
        $ignorePackageValue = false
    ) {
        $zone = $this->_getZoneFromCountry(
            $countryCode,
            $this->mappingCountryToZone
        );

        $methods = $this->_getZoneToMethod($zone, $this->mappingZoneToMethod);

        if ($ignorePackageValue) {
            $methodMeta = $this->_getMethodToMetaAll(
                $methods,
                $this->mappingMethodToMeta
            );
        } else {
            $methodMeta = $this->_getMethodToMeta(
                $packageValue,
                $methods,
                $this->mappingMethodToMeta
            );
        }

        return $this->_getMethodToPrice(
            $packageWeight,
            $methodMeta,
            $this->mappingDeliveryToPrice
        );
    }

    /**
     * Method to return a 2d array of world zones a country
     * (by its country code) is located in.
     *
     * @param string $countryCode          - Country code to filter on
     * @param array  $mappingCountryToZone - Array for mapped countries to zones
     *
     * @return array
     */
    private function _getZoneFromCountry($countryCode, $mappingCountryToZone)
    {
        $zones = [];

        if (isset($mappingCountryToZone[$countryCode])) {
            $zoneList = $mappingCountryToZone[$countryCode];

            foreach ($zoneList as $zone) {
                $zones[] = $zone[static::COUNTRY_ZONE_COLUMN];
            }
        }

        return $zones;
    }

    /**
     * Method to return a 2d array of possible delivery methods based
     * on the given world zones a country is in.
     *
     * @param array $zones               - Zone to restrict methods to
     * @param array $mappingZoneToMethod - ZonesToMethods to filter on
     *
     * @return array
     */
    private function _getZoneToMethod(
        $zones,
        $mappingZoneToMethod
    ) {
        $mappingZoneData = [];

        foreach ($zones as $zone) {
            foreach ($mappingZoneToMethod[$zone] as $method) {
                $mappingZoneData[] = $method[static::METHOD_COLUMN];
            }
        }

        return $mappingZoneData;
    }

    /**
     * Method to return a 2d array of sorted shipping methods based on
     * the weight of the item and the allowed shipping methods. Returns
     * a 2d array to be converting into objects by the RoyalMailMethod
     * class. Also adds the pretty text from the meta table to the
     * correct shipping method, to allow for less text in the delivery
     * to price csv.
     *
     * @param int   $packageWeight          - The weight of the package
     * @param array $methodMetas            - Sorted methods to meta
     * @param array $mappingDeliveryToPrice - Sorted delivery to price
     *
     * @return array
     */
    private function _getMethodToPrice(
        $packageWeight,
        $methodMetas,
        $mappingDeliveryToPrice
    ) {
        $rates = [];

        foreach ($methodMetas as $method => $methodMeta) {
            $methodRates = $mappingDeliveryToPrice[$method];

            foreach ($methodRates as $methodRate) {
                if ($packageWeight >= $methodRate[self::MIN_WEIGHT_COLUMN]
                    && $packageWeight <= $methodRate[self::MAX_WEIGHT_COLUMN]
                ) {
                    $rate = [
                        'id' => $method,
                        'code' => $methodMeta[0][self::CODE_COLUMN],
                        'name' => $methodMeta[0][self::NAME_COLUMN],
                        'minimumWeight' =>
                            (double)$methodRate[self::MIN_WEIGHT_COLUMN],
                        'maximumWeight' =>
                            (double)$methodRate[self::MAX_WEIGHT_COLUMN],
                        'price' => (double)$methodRate[self::PRICE_COLUMN],
                        'insuranceValue' =>
                            (int)$methodRate[self::INSURANCE_VALUE_COLUMN],
                    ];

                    if (isset($methodRate[self::SIZE_COLUMN])) {
                        $rate['size'] = $methodRate[self::SIZE_COLUMN];
                    }

                    $rates[] = $rate;
                }
            }
        }

        return array_values($rates);
    }

    /**
     * Method to return a 2d array of the meta data for each
     * given allowed shipping method and the given package
     * value.
     *
     * @param int   $packageValue        - Package value to filter methods on
     * @param array $methods             - SortedZoneToMethods to filter with
     * @param array $mappingMethodToMeta - MethodToMeta to filter on
     *
     * @return array
     */
    private function _getMethodToMeta(
        $packageValue,
        $methods,
        $mappingMethodToMeta
    ) {
        $mappingZoneMethodData = $this->_getMethodToMetaAll(
            $methods,
            $mappingMethodToMeta
        );

        foreach ($mappingZoneMethodData as $method => $methodMeta) {
            if ($packageValue < $methodMeta[0][static::METHOD_MIN_VALUE]
                || $packageValue > $methodMeta[0][static::METHOD_MAX_VALUE]
            ) {
                unset($mappingZoneMethodData[$method]);
            }
        }

        return $mappingZoneMethodData;
    }

    /**
     * Method to return a 2d array of the meta data for each
     * given allowed shipping method, not based on the price
     * of the item. Returns all possible available methods
     * that are available.
     *
     * @param array $methods             - Sorted array of zone to methods
     * @param array $mappingMethodToMeta - Sorted array of methods to the meta
     *
     * @return array
     */
    private function _getMethodToMetaAll($methods, $mappingMethodToMeta)
    {
        return array_intersect_key($mappingMethodToMeta, array_flip($methods));
    }

    /**
     * Reads the given csv in to a 2d array
     *
     * @param string $filename  - The filename of the csv
     * @param string $delimiter - The delimiter
     *
     * @return array
     * @throws \Exception
     */
    private function _csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception(
                "Unable to load the Royal Mail price data csv for
                '$filename'. Ensure that the data folder contains all the necessary
                 csvs."
            );
        }

        $header = null;
        $data = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $key = $row[0];

                if (isset($data[$key])) {
                    $data[$key][] = $row;
                } else {
                    $data[$row[0]] = [$row];
                }
            }

            fclose($handle);
        }
        return $data;
    }


    /**
     * Maps countries to zones.
     *
     * @return array
     */
    public function getMappingCountryToZone()
    {
        return $this->mappingCountryToZone;
    }

    /**
     * Maps zones to methods.
     *
     * @return array
     */
    public function getMappingZoneToMethod()
    {
        return $this->mappingZoneToMethod;
    }

    /**
     * Map methods to meta information. This includes the insurance
     * amount, and the corresponding price levels
     *
     * @return array
     */
    public function getMappingMethodToMeta()
    {
        return $this->mappingMethodToMeta;
    }

    /**
     * Maps method to prices (rates) based on weight boundaries
     *
     * @return array
     */
    public function getMappingDeliveryToPrice()
    {
        return $this->mappingDeliveryToPrice;
    }
}
