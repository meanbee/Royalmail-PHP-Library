<?php
namespace Meanbee;

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

define('DOCUMENT_ROOT_1', dirname(realpath(__FILE__)) . '/');

class data
{
    // Locations of the csv files used
    protected $_csvCountryCode =  DOCUMENT_ROOT_1 .'../data/1_countryToZone.csv';
    protected $_csvZoneToDeliverMethod =  DOCUMENT_ROOT_1 .'../data/2_zoneToDeliveryMethod.csv';
    protected $_csvDeliveryMethodMeta =  DOCUMENT_ROOT_1 .'../data/3_deliveryMethodMeta.csv';
    protected $_csvDeliveryToPrice =  DOCUMENT_ROOT_1 .'../data/4_deliveryToPrice.csv';


    // 1st array used, stores the csv of country to zone
    public $mappingCountryToZone = array();

    // 2nd array used, stores the csv of zone to method
    public $mappingZoneToMethod = array();

    // 3rd array used, stores the csv of shipping method
    // to the meta information. This includes the insurance
    // amount, and the corresponding price levels
    public $mappingMethodToMeta = array();

    // 4th array used, stores the csv of the delivery method
    // to the weight and price
    public $mappingDeliveryToPrice = array();


    function __construct()
    {
        $this->mappingCountryToZone = $this->csvToArray($this->_csvCountryCode);
        $this->mappingZoneToMethod = $this->csvToArray($this->_csvZoneToDeliverMethod);
        $this->mappingMethodToMeta = $this->csvToArray($this->_csvDeliveryMethodMeta);
        $this->mappingDeliveryToPrice = $this->csvToArray($this->_csvDeliveryToPrice);
    }

    /**
     * Reads the csv given csv in to a 2d array
     *
     * @param string $filename
     * @param string $delimiter
     *
     * @return array
     * @throws \Exception
     */
    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \Exception("Unable to load the Royal Mail price data csv for '$filename'.
            Ensure that the app/ directory is in your include path.");
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
}