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
 * Class DataTest
 * Test class to run phpunit tests on the library.
 *
 * @category Meanbee
 * @package  Meanbee\Royalmail
 * @author   Meanbee Limited <hello@meanbee.com>
 * @license  OSL v. 3.0
 * @link     http://github.com/meanbee/royalmail-php-library
 */
class DataTest extends \PHPUnit_Framework_TestCase
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
     * Variable for data class
     *
     * @var Data $_dataClass
     */
    private $_dataClass;

    private $_testDataClassArray;

    /**
     * Setup the necessary classes and data
     *
     * @return null
     */
    public function setUp()
    {
        $this->_dataClass = new Data();

        $this->_testDataClassArray = array(
            'id' => 'TEST_ID',
            'code' => 'testcode',
            'minimumWeight' => 1.00,
            'maximumWeight' => 5.00,
            'price' => 0.99,
            'insuranceValue' => 10,
            'name' => 'Test',
            'size' => 'Small',
        );
    }

    /**
     * Cleans up the used classes
     *
     * @return null
     */
    public function tearDown()
    {
        $this->_dataClass = null;
    }

    /**
     * Test to compare the returned data from the Data class to expected values
     *
     * @return null
     */
    public function testRoyalmailMethodRealValues()
    {
        $calculatedMethods = $this->_dataClass->calculateMethods('GB', 19.99, 0.050);
        foreach ($calculatedMethods as $calculatedMethod => $arrayContents) {
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['id']
                ),
                gettype($arrayContents['id']),
                "id array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['code']
                ),
                gettype($arrayContents['code']),
                "code array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['minimumWeight']
                ),
                gettype($arrayContents['minimumWeight']),
                "minimumWeight array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['maximumWeight']
                ),
                gettype($arrayContents['maximumWeight']),
                "maximumWeight array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['price']
                ),
                gettype($arrayContents['price']),
                "price array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['insuranceValue']
                ),
                gettype($arrayContents['insuranceValue']),
                "insuranceValue array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['name']
                ),
                gettype($arrayContents['name']),
                "name array value not equal to correct type."
            );
            $this->assertEquals(
                gettype(
                    $this->_testDataClassArray['size']
                ),
                gettype($arrayContents['size']),
                "size array value not equal to correct type."
            );
        }
    }

    /**
     * Test to ensure the only the expected empty array
     * is returned from incorrect data to the data class
     *
     * @return null
     */
    public function testRoyalmailMethodFake()
    {
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                'GASD', "aSDASD", "ASDASD"
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                123123123, "asdasd", "asdadasd"
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                123123, 123123, "ASDASD"
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                123123123, 123123123, 123123123
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                'GB', "aSD!!ASD", 0.100
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                'GB', 123123123123, 0.100
            )
        );
    }

    /**
     * Test to ensure that only the expected empty array
     * is returned from null and incorrect data
     * from the Data class
     *
     * @return null
     */
    public function testRoyalmailMethodNull()
    {
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                null, 123123123123, 0.100
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                null, null, 0.100
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                'GB', null, 0.100
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                'GB', null, null
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                'GB', 123123123123, null
            )
        );
        $this->assertEmpty(
            $this->_dataClass->calculateMethods(
                null, null, null
            )
        );
    }

    /**
     * Test for insurance value checking that the correct insurance value is being
     * used in the CSV files
     *
     * @return null
     */
    public function testInsuranceValue()
    {
        $deliveryToPrice = $this->_dataClass->getMappingDeliveryToPrice();
        foreach ($this->_dataClass->getMappingMethodToMeta() as $id => $methodMeta) {
            $methodRates = $deliveryToPrice[$id];

            foreach ($methodRates as $methodRate) {
                if ($methodRate[self::INSURANCE_ROW_PRICE_CSV] != "") {
                    $this->assertEquals(
                        $methodMeta[0][self::INSURANCE_ROW_META_CSV],
                        $methodRate[self::INSURANCE_ROW_PRICE_CSV]
                    );
                }
            }
        }
    }
}
