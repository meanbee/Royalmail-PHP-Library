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
 * Class MethodTest
 * Test class to run phpunit tests on the library.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class MethodTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Sample method data
     *
     * @var array
     */
    protected $data = [
        'id' => 'MEANBEE_TEST_RATE',
        'code' => 'meanbeetest',
        'name' => 'Meanbee Test',
        'countryCode' => 'GB',
        'price' => '0.99',
        'insuranceValue' => '20',
        'minimumWeight' => '0',
        'maximumWeight' => '200',
        'size' => 'SMALL',
    ];

    /**
     * Method Class
     *
     * @var Method
     */
    protected $method;


    /**
     * Set up
     *
     * @return null
     */
    public function setUp()
    {
        $this->method = new Method(
            $this->data['id'],
            $this->data['code'],
            $this->data['name'],
            $this->data['countryCode'],
            $this->data['price'],
            $this->data['insuranceValue'],
            $this->data['minimumWeight'],
            $this->data['maximumWeight'],
            $this->data['size']
        );
    }

    /**
     * Test getId
     *
     * @return null
     */
    public function testGetId()
    {
        $this->assertEquals($this->data['id'], $this->method->getId());
    }

    /**
     * Test getCode
     *
     * @return null
     */
    public function testGetCode()
    {
        $this->assertEquals($this->data['code'], $this->method->getCode());
    }

    /**
     * Test getName
     *
     * @return null
     */
    public function testGetName()
    {
        $this->assertEquals($this->data['name'], $this->method->getName());
    }

    /**
     * Test getCountryCode
     *
     * @return null
     */
    public function testGetCountryCode()
    {
        $this->assertEquals(
            $this->data['countryCode'],
            $this->method->getCountryCode()
        );
    }

    /**
     * Test getPrice
     *
     * @return null
     */
    public function testGetPrice()
    {
        $this->assertEquals($this->data['price'], $this->method->getPrice());
    }

    /**
     * Test getInsuranceValue
     *
     * @return null
     */
    public function testGetInsuranceValue()
    {
        $this->assertEquals(
            $this->data['insuranceValue'],
            $this->method->getInsuranceValue()
        );
    }

    /**
     * Test getMinimumWeight
     *
     * @return null
     */
    public function testGetMinimumWeight()
    {
        $this->assertEquals(
            $this->data['minimumWeight'],
            $this->method->getMinimumWeight()
        );
    }

    /**
     * Test getMaximumWeight
     *
     * @return null
     */
    public function testGetMaximumWeight()
    {
        $this->assertEquals(
            $this->data['maximumWeight'],
            $this->method->getMaximumWeight()
        );
    }

    /**
     * Test getSize
     *
     * @return null
     */
    public function testGetSize()
    {
        $this->assertEquals(
            $this->data['size'],
            $this->method->getSize()
        );
    }
}
