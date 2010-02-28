<?php
use \seasar\container\S2ApplicationContext as s2app;

class Service_CalcTest extends PHPUnit_Framework_TestCase {

    public function testAdd() {
        $this->assertEquals(3, $this->service->add(1, 2));
    }

    public function setUp() {
        s2app::init();
        $this->service = s2app::get('Service_Calc');
    }

    public function tearDown() {
        $this->service = null;
    }
}


