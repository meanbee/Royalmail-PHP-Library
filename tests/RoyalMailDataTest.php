<?php

use Meanbee\data;

class RoyalmailTest extends PHPUnit_Framework_TestCase
{

    private $royalMailDataClass;

    public function setUp()
    {
        $this->royalMailDataClass = new data();
    }

    public function tearDown()
    {
        $this->royalMailDataClass = null;
    }

    public function testDataClass()
    {
        //TODO-Aaron Test constructor on data class to ensure csvs work.



//        $dataClass = new data();
//        $mock = $this->getMockBuilder($dataClass)
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $mock->expects($this->once())
//            ->method('')

    }
}
