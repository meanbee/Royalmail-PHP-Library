<?php namespace Meanbee\Royalmail;

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

    /** @var Carrier $carrier */
    private $carrier;

    /** @var Data $dataClass */
    private $dataClass;
    private $emptyArray;
    private $testDataClassArray;

    /**
     * Setup the necessary classes and data
     */
    public function setUp()
    {
        /** @var Carrier */
        $this->carrier = new Carrier();
        $this->dataClass = new Data(
            $this->carrier->getCsvCountryCode(),
            $this->carrier->getCsvZoneToDeliveryMethod(),
            $this->carrier->getCsvDeliveryMethodMeta(),
            $this->carrier->getCsvDeliveryToPrice(),
            $this->carrier->getCsvCleanNameToMethod(),
            $this->carrier->getCsvCleanNameMethodGroup()
        );

        $this->emptyArray = [];
        $this->testDataClassArray = array(
            'shippingMethodName'      => 'test',
            'minimumWeight'           => 1.00,
            'maximumWeight'           => 5.00,
            'methodPrice'             => 0.99,
            'insuranceValue'          => 10,
            'shippingMethodNameClean' => 'Test',
            'size'                    => 'Small',
        );
    }

    /**
     * Cleans up the used classes
     */
    public function tearDown()
    {
        $this->carrier = null;
        $this->dataClass = null;
    }

    /**
     * Test to ensure that the calculate method class is returning
     */
    public function testRoyalmailClassRealValues()
    {
        $this->assertNotEmpty($this->carrier->getRates('GB', 20, 0.050));
    }

    /**
     * Test to ensure that the calculate method class is returning rates with
     * the ignore insurance flag set to true.
     */
    public function testRoyalmailClassRealValuesAll()
    {
        $this->assertNotEmpty($this->carrier->getRates('GB', 20, 0.050, true));

    }

    /**
     * Test to compare the returned data from the Data class to expected values
     */
    public function testRoyalmailMethodRealValues()
    {
        $calculatedMethods = $this->dataClass->calculateMethods('GB', 19.99, 0.050);
        foreach ($calculatedMethods as $calculatedMethod => $arrayContents) {
            $this->assertEquals(gettype($this->testDataClassArray['shippingMethodName']),
                gettype($arrayContents['shippingMethodName']), "shippingMethodName array value not equal to correct type.");
            $this->assertEquals(gettype($this->testDataClassArray['minimumWeight']),
                gettype($arrayContents['minimumWeight']), "minimumWeight array value not equal to correct type.");
            $this->assertEquals(gettype($this->testDataClassArray['maximumWeight']),
                gettype($arrayContents['maximumWeight']), "maximumWeight array value not equal to correct type.");
            $this->assertEquals(gettype($this->testDataClassArray['methodPrice']),
                gettype($arrayContents['methodPrice']), "methodPrice array value not equal to correct type.");
            $this->assertEquals(gettype($this->testDataClassArray['insuranceValue']),
                gettype($arrayContents['insuranceValue']), "insuranceValue array value not equal to correct type.");
            $this->assertEquals(gettype($this->testDataClassArray['shippingMethodNameClean']),
                gettype($arrayContents['shippingMethodNameClean']), "shippingMethodNameClean array value not equal to correct type.");
            $this->assertEquals(gettype($this->testDataClassArray['size']), gettype($arrayContents['size']),
                "size array value not equal to correct type.");

        }
    }

    /**
     * Test to ensure the only the expected empty array is returned from incorrect data to the data class
     */
    public function testRoyalmailMethodFake()
    {
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GASD', "aSDASD", "ASDASD"));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(123123123, "asdasd", "asdadasd"));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(123123, 123123, "ASDASD"));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(123123123, 123123123, 123123123));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', "aSD!!ASD", 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', 123123123123, 0.100));
    }

    /**
     * Test to ensure that only the expected empty array is returned from null and incorrect data
     * from the Data class
     */
    public function testRoyalmailMethodNull()
    {
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(null, 123123123123, 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(null, null, 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', null, 0.100));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', null, null));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods('GB', 123123123123, null));
        $this->assertEquals($this->emptyArray, $this->dataClass->calculateMethods(null, null, null));
    }

    /**
     * Test to ensure that only the expected empty array is returned from incorrect
     * data from the CalculateMethod class
     */
    public function testRoyalmailClassFake()
    {
        $this->assertEquals(
            $this->emptyArray,
            $this->carrier->getRates('GASD', "aSDASD", "ASDASD"));
        $this->assertEquals(
            $this->emptyArray,
            $this->carrier->getRates(123123123, "asdasd", "asdadasd"));
        $this->assertEquals(
            $this->emptyArray,
            $this->carrier->getRates(123123, 123123, "ASDASD"));
        $this->assertEquals(
            $this->emptyArray,
            $this->carrier->getRates(123123123, 123123123, 123123123));
        $this->assertEquals(
            $this->emptyArray,
            $this->carrier->getRates('GB', "aSD!!ASD", 0.100));
        $this->assertEquals(
            $this->emptyArray,
            $this->carrier->getRates('GB', 123123123123, 0.100));
    }

    /**
     * Test to ensure that only the expected empty array is returned from null
     * and incorrect data from the CalculateMethod class
     */
    public function testRoyalmailClassNull()
    {
        $this->assertEquals($this->emptyArray, $this->carrier->getRates(null, 123123123123, 0.100));
        $this->assertEquals($this->emptyArray, $this->carrier->getRates(null, null, 0.100));
        $this->assertEquals($this->emptyArray, $this->carrier->getRates('GB', null, 0.100));
        $this->assertEquals($this->emptyArray, $this->carrier->getRates('GB', null, null));
        $this->assertEquals($this->emptyArray, $this->carrier->getRates('GB', 123123123123, null));
        $this->assertEquals($this->emptyArray, $this->carrier->getRates(null, null, null));
    }

    /**
     * Test co compare the meta names vs the clean name to check that the correct value
     * exists and is being used.
     */
    public function testMethodToMetaVsCleanName()
    {
        foreach ($this->dataClass->getMappingMethodToMeta() as $array => $data) {
            $methodNotExist = false;
            foreach ($this->dataClass->getMappingCleanNameToMethod() as $method => $methodData) {
                // Check the the names are equal
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_CLEANNAME_CSV]) {
                    $methodNotExist = true;
                    $this->assertEquals($data[self::METHOD_CLEAN_NAME_ROW_META_CSV],
                        $methodData[self::METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV],
                        sprintf("Clean names %s and %s were not equal", $data[self::METHOD_CLEAN_NAME_ROW_META_CSV],
                            $methodData[self::METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV]));
                }

            }
            $this->assertTrue($methodNotExist,
                sprintf("%s was not found in CleanNameToMethod csv$.", $data[self::METHOD_NAME_ROW_META_CSV]));
        }
    }

    /**
     * Test for insurance value checking that the correct insurance value is being
     * used in the CSV files
     */
    public function testInsuranceValue()
    {
        foreach ($this->dataClass->getMappingMethodToMeta() as $array => $data) {
            foreach ($this->dataClass->getMappingDeliveryToPrice() as $method => $methodData) {
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_PRICE_CSV]) {
                    if ($methodData[self::INSURANCE_ROW_PRICE_CSV] != "") {
                        $this->assertEquals($data[self::INSURANCE_ROW_META_CSV],
                            $methodData[self::INSURANCE_ROW_PRICE_CSV],
                            sprintf("Insurance values %s from mappingMethodToMeta and %s from mappingDeliveryToPrice were not equal.",
                                $data[self::INSURANCE_ROW_META_CSV],
                                $methodData[self::INSURANCE_ROW_PRICE_CSV]));
                    }
                }
            }
        }

        foreach ($this->dataClass->getMappingMethodToMeta() as $array => $data) {
            foreach ($this->dataClass->getMappingCleanNameToMethod() as $method => $methodData) {
                if ($data[self::METHOD_NAME_ROW_META_CSV] == $methodData[self::METHOD_NAME_ROW_CLEANNAME_CSV]) {
                    if ($methodData[self::INSURANCE_ROW_CLEANNAME_CSV] != "") {
                        $this->assertEquals($data[self::INSURANCE_ROW_META_CSV],
                            $methodData[self::INSURANCE_ROW_CLEANNAME_CSV],
                            sprintf("Insurance values %s from mappingMethodToMeta and %s from mappingCleanNameToMethod were not equal.",
                                $data[self::INSURANCE_ROW_META_CSV],
                                $methodData[self::INSURANCE_ROW_CLEANNAME_CSV]));
                    }
                }
            }

        }
    }

    /**
     * Test to compare the method clean name vs the method group, ensuring that
     * the clean names are correct and exists.
     */
    public function testCleanNameVsMethodGroup()
    {
        foreach ($this->dataClass->getMappingCleanNameToMethod() as $array => $data) {
            foreach ($this->dataClass->getMappingCleanNameMethodGroup() as $method => $methodData) {
                if ($data[self::METHOD_CLEAN_NAME_ROW_CLEANNAME_CSV] == $methodData[self::METHOD_CLEAN_NAME_ROW_CLEANNAMEGROUP_CSV]) {
                    $this->assertEquals($data[self::METHOD_CLEAN_NAME_GROUP_CLEANNAME_CSV],
                        $methodData[self::METHOD_CLEAN_NAME_GROUP_CLEANNAMEGROUP_CSV],
                        sprintf("Clean names %s from mappingCleanNameToMethod and %s from mappingCleanNameToMethodGroup were not equal.",
                            $data[self::METHOD_CLEAN_NAME_GROUP_CLEANNAME_CSV],
                            $methodData[self::METHOD_CLEAN_NAME_GROUP_CLEANNAMEGROUP_CSV]));
                }
            }
        }
    }

    /**
     * Test get a full list of royal mail methods
     */
    public function testGetAllMethods()
    {
        /** @var array $methods */
        $methods = $this->carrier->getAllMethods();

        $this->assertInternalType('array', $methods);
        $this->assertTrue(count($methods) > 0);

        foreach ($methods as $code => $name) {
            $this->assertInternalType('string', $code);
            $this->assertFalse(strpos($code, ' '));
            $this->assertInternalType('string', $name);
        }
    }
}
