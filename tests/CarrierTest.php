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
 * Class CarrierTest
 * Test class to run phpunit tests on the library.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class CarrierTest extends \PHPUnit_Framework_TestCase
{

    const METHOD_NAME_ROW_META_CSV = 0;
    const METHOD_NAME_ROW_CLEANNAME_CSV = 2;
    const METHOD_NAME_ROW_PRICE_CSV = 0;
    const METHOD_CLEAN_NAME_ROW_META_CSV = 4;
    const METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV = 1;
    const METHOD_CLEAN_NAME_ROW_CLEANNAMEGROUP_CSV = 1;
    const METHOD_CLEAN_NAME_GROUP_CLEANNAME_CSV = 0;
    const METHOD_CLEAN_NAME_GROUP_CLEANNAMEGROUP_CSV = 0;
    const INSURANCE_ROW_META_CSV = 3;
    const INSURANCE_ROW_PRICE_CSV = 4;
    const INSURANCE_ROW_CLEANNAME_CSV = 5;

    /**
     * Variable for carrier class
     *
     * @var Carrier $_carrier
     */
    private $_carrier;

    /**
     * Setup the necessary classes and data
     *
     * @return null
     */
    public function setUp()
    {
        /**
         * Set the carrier class
         *
         * @var Carrier
         */
        $this->_carrier = new Carrier();
    }

    /**
     * Cleans up the used classes
     *
     * @return null
     */
    public function tearDown()
    {
        $this->_carrier = null;
    }

    /**
     * Test to ensure that the calculate method class is returning
     *
     * @return null
     */
    public function testRoyalmailClassRealValues()
    {
        $this->assertNotEmpty($this->_carrier->getRates('GB', 20, 0.050));
    }

    /**
     * Test to ensure that the calculate method class is returning rates with
     * the ignore insurance flag set to true. 37 methods are expected to be
     * returned.
     *
     * @return null
     */
    public function testRoyalmailClassRealValuesAll()
    {
        $this->assertCount(
            37,
            $this->_carrier->getRates('GB', 20, 0.050, true)
        );
    }

    /**
     * Assert that Method[] return type of rate call
     *
     * @return null
     */
    public function testGetRatesReturnType()
    {
        $rates = $this->_carrier->getRates('GB', 20, 0.050, true);
        foreach ($rates as $rate) {
            $this->assertInstanceOf('Meanbee\Royalmail\Method', $rate);
        }
    }

    /**
     * Test to ensure that the calculate method class is returning rates with
     * the ignore insurance flag set to false. 30 methods are expected to be
     * returned.
     *
     * @return null
     */
    public function testRoyalmailClassRealValuesAllFalse()
    {
        $this->assertCount(
            30,
            $this->_carrier->getRates('GB', 20, 0.050, false)
        );
    }

    /**
     * Test to ensure that only the expected empty array is returned from incorrect
     * data from the CalculateMethod class
     *
     * @return null
     */
    public function testRoyalmailClassFake()
    {
        $this->assertEmpty($this->_carrier->getRates('GASD', "aSDASD", "ASDASD"));
        $this->assertEmpty($this->_carrier->getRates(123123, "asdasd", "asdadasd"));
        $this->assertEmpty($this->_carrier->getRates(123123, 123123, "ASDASD"));
        $this->assertEmpty($this->_carrier->getRates(12312312, 12312312, 12312312));
        $this->assertEmpty($this->_carrier->getRates('GB', "aSD!!ASD", 0.100));
        $this->assertEmpty($this->_carrier->getRates('GB', 123123123123, 0.100));
    }

    /**
     * Test to ensure that only the expected empty array is returned from null
     * and incorrect data from the CalculateMethod class
     *
     * @return null
     */
    public function testRoyalmailClassNull()
    {
        $this->assertEmpty(
            $this->_carrier->getRates(
                null, 123123123123, 0.100
            )
        );
        $this->assertEmpty(
            $this->_carrier->getRates(
                null, null, 0.100
            )
        );
        $this->assertEmpty(
            $this->_carrier->getRates(
                'GB', null, 0.100
            )
        );
        $this->assertEmpty(
            $this->_carrier->getRates(
                'GB', null, null
            )
        );
        $this->assertEmpty(
            $this->_carrier->getRates(
                'GB', 123123123123, null
            )
        );
        $this->assertEmpty(
            $this->_carrier->getRates(
                null, null, null
            )
        );
    }

    /**
     * Test get a full list of royal mail methods
     *
     * @return null
     */
    public function testGetAllMethods()
    {
        /**
         * All methods from the carrier class
         *
         * @var array $methods
         */
        $methods = $this->_carrier->getAllMethods();

        $this->assertInternalType('array', $methods);
        $this->assertTrue(count($methods) > 0);

        foreach ($methods as $code => $name) {
            $this->assertInternalType('string', $code);
            $this->assertFalse(strpos($code, ' '));

            $this->assertInternalType('string', $name);
            $this->assertTrue(strpos($name, ' ') !== false);
        }
    }
}
