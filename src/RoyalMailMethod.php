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
 * @package    Royalmail-PHP-Library
 * @copyright  Copyright (c) 2015 Meanbee Internet Solutions (http://www.meanbee.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RoyalMailMethod
{

    public $shippingMethodName;
    public $shippingMethodNameClean;
    public $countryCode;
    public $methodPrice;
    public $insuranceValue;
    public $minimumWeight;
    public $maximumWeight;

    public function getShippingMethods($countryCode, $packageValue, $packageWeight)
    {

        $royalMail = new RoyalMail();
        $sortedDeliveryMethods = array($royalMail->getMethods($countryCode, $packageValue, $packageWeight));
        $results = [];

        foreach ($sortedDeliveryMethods as $shippingMethod) {
            foreach ($shippingMethod as $item) {
                foreach ($item as $value) {
                    $method = new RoyalMailMethod();
                    $method->countryCode = $countryCode;
                    $method->shippingMethodName = $value['shippingMethodName'];
                    $method->minimumWeight = $value['minimumWeight'];
                    $method->maximumWeight = $value['maximumWeight'];
                    $method->methodPrice = $value['methodPrice'];
                    $method->insuranceValue = $value['insuranceValue'];
                    $method->shippingMethodNameClean = $value['shippingMethodNameClean'];

                    $results[] = $method;
                }
            }
        }
        return $results;
    }
}