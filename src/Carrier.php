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
 * Class Carrier
 * Provides methods to get rates from the csv files, interacts with the data class
 * to return arrays of methods.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class Carrier implements CarrierInterface
{

    /**
     * Data resource class
     *
     * @var Data|null
     */
    protected $data;

    /**
     * Carrier constructor.
     *
     * @param string|null $csvCountryCode          - csv for Country Code
     * @param string|null $csvZoneToDeliveryMethod - csv for ZoneToDeliveyMethod
     * @param string|null $csvDeliveryMethodMeta   - csv for DeliveryMethodMeta
     * @param string|null $csvDeliveryToPrice      - csv for DeliveryToPrice
     */
    public function __construct(
        $csvCountryCode = null,
        $csvZoneToDeliveryMethod = null,
        $csvDeliveryMethodMeta = null,
        $csvDeliveryToPrice = null
    ) {
        $this->data = new Data(
            $csvCountryCode,
            $csvZoneToDeliveryMethod,
            $csvDeliveryMethodMeta,
            $csvDeliveryToPrice
        );
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
     * at the users discretion. This can be useful if the user
     * wants to get all available methods for the country code
     * and weight, ignoring the price of the package.
     *
     * @param string $country_code         - The country code being shipped to
     * @param int    $package_value        - The total package value
     * @param int    $package_weight       - The total package weight
     * @param bool   $ignore_package_value - Flag to allow ignoring the
     *                                       package weight
     *
     * @return array                       - Array of all methods returned
     */
    public function getRates(
        $country_code,
        $package_value,
        $package_weight,
        $ignore_package_value = false
    ) {
        $sortedDeliveryMethods = [
            $this->data->calculateMethods(
                $country_code,
                $package_value,
                $package_weight,
                $ignore_package_value
            )
        ];

        $results = [];

        foreach ($sortedDeliveryMethods as $shippingMethod) {
            foreach ($shippingMethod as $item) {
                $method = new Method(
                    $item['id'],
                    $item['code'],
                    $item['name'],
                    $country_code,
                    $item['price'],
                    $item['insuranceValue'],
                    $item['minimumWeight'],
                    $item['maximumWeight'],
                    isset($item['size']) ? $item['size'] : null
                );

                $results[] = $method;
            }
        }
        return $results;
    }

    /**
     * Get full list of available RoyalMail Methods
     *
     * @return array
     */
    public function getAllMethods()
    {
        $methods = [];
        foreach ($this->data->getMappingMethodToMeta() as $item) {
            $methods[$item[0][Data::CODE_COLUMN]] = $item[0][Data::NAME_COLUMN];
        }

        return $methods;
    }
}
