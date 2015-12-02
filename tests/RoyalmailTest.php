<?php

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

use Meanbee\RoyalMail;
use Meanbee\RoyalMailMethod;
use Meanbee\data;

class RoyalmailTest extends PHPUnit_Framework_TestCase
{

    private $royalMailClass;
    private $royalMailMethod;
    private $royalMailData;

    public function setUp()
    {
        $this->royalMailClass = new RoyalMail();
        $this->royalMailMethod = new RoyalMailMethod();
        $this->royalMailData = new data();

    }

    public function tearDown()
    {
        $this->royalMailClass = null;
        $this->royalMailMethod = null;
        $this->royalMailData = null;
    }

    public function testRoyalMailClass()
    {
        $this->royalMailClass->getMethods('GB', 20, 0.050);
    }

    public function testRoyalMailMethodRealValues()
    {
        $this->royalMailMethod->getShippingMethods('GB', 19.99, 0.050);
        $this->royalMailMethod->getShippingMethods('GB', 19.99, 0.050);
        $this->royalMailMethod->getShippingMethods('GG', 10, 0.100);
        $this->royalMailMethod->getShippingMethods('GB', 200, 0.010);

    }

    public function testRoyalMailMethodFake()
    {
        $this->royalMailMethod->getShippingMethods('GASD', "aSDASD", "ASDASD");
        $this->royalMailMethod->getShippingMethods(123123123, "asdasd", "asdadasd");
        $this->royalMailMethod->getShippingMethods(123123, 123123, "ASDASD");
        $this->royalMailMethod->getShippingMethods(123123123, 123123123, 123123123);
        $this->royalMailMethod->getShippingMethods('GB', "aSD!!ASD", 0.100);
        $this->royalMailMethod->getShippingMethods('GB', 123123123123, 0.100);
    }

    public function testRoyalMailMethodNull()
    {
        $this->royalMailMethod->getShippingMethods(null, 123123123123, 0.100);
        $this->royalMailMethod->getShippingMethods(null, null, 0.100);
        $this->royalMailMethod->getShippingMethods('GB', null, 0.100);
        $this->royalMailMethod->getShippingMethods('GB', null, null);
        $this->royalMailMethod->getShippingMethods('GB', 123123123123, null);
        $this->royalMailMethod->getShippingMethods(null, null, null);
    }

    public function testRoyalMailClassReal()
    {
        $this->royalMailClass->getMethods('GB', 19.99, 0.050);
        $this->royalMailClass->getMethods('GB', 19.99, 0.050);
        $this->royalMailClass->getMethods('GG', 10, 0.100);
        $this->royalMailClass->getMethods('GB', 200, 0.010);

    }

    public function testRoyalMailClassFake()
    {
        $this->royalMailClass->getMethods('GASD', "aSDASD", "ASDASD");
        $this->royalMailClass->getMethods(123123123, "asdasd", "asdadasd");
        $this->royalMailClass->getMethods(123123, 123123, "ASDASD");
        $this->royalMailClass->getMethods(123123123, 123123123, 123123123);
        $this->royalMailClass->getMethods('GB', "aSD!!ASD", 0.100);
        $this->royalMailClass->getMethods('GB', 123123123123, 0.100);
    }

    public function testRoyalMailClassNull()
    {
        $this->royalMailClass->getMethods(null, 123123123123, 0.100);
        $this->royalMailClass->getMethods(null, null, 0.100);
        $this->royalMailClass->getMethods('GB', null, 0.100);
        $this->royalMailClass->getMethods('GB', null, null);
        $this->royalMailClass->getMethods('GB', 123123123123, null);
        $this->royalMailClass->getMethods(null, null, null);
    }

}