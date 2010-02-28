<?php
class Service_CalcTest extends PHPUnit_Framework_TestCase {

    public function testAdd() {
        $this->assertEquals(3, $this->service->add(1, 2));
    }

    public function setUp() {
        s2init();
        $this->service = s2get('Service_Calc');
    }

    public function tearDown() {
        $this->service = null;
    }
}


