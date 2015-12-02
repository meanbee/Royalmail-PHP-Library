<?php

use Meanbee\RoyalMail;
use Meanbee\RoyalMailMethod;

class RoyalmailTest extends PHPUnit_Framework_TestCase
{

    private $royalMailClass;
    private $royalMailMethod;

    public function setUp()
    {
        $this->royalMailClass = new RoyalMail();
        $this->royalMailMethod = new RoyalMailMethod();
    }

    public function tearDown()
    {
        $this->royalMailClass = null;
        $this->royalMailMethod = null;
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

    public function testRoyalMailMethodFakeValues()
    {
        $this->royalMailMethod->getShippingMethods('GASD', "aSDASD", "ASDASD");
        $this->royalMailMethod->getShippingMethods(123123123, "asdasd", "asdadasd");
        $this->royalMailMethod->getShippingMethods(123123, 123123, "ASDASD");
        $this->royalMailMethod->getShippingMethods(123123123, 123123123, 123123123);
        $this->royalMailMethod->getShippingMethods('GB', "aSDASD", 0.100);
        $this->royalMailMethod->getShippingMethods('GB', 123123123123, 0.100);

    }

}