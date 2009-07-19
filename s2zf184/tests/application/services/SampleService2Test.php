<?php
use \seasar\container\S2ApplicationContext as s2app;

class Service_SampleService2Test extends PHPUnit_Framework_TestCase {

    public function testFindAllCd() {
        $rows = $this->service->findAllCd();
        $this->assertEquals(1, count($rows));
    }

    public function testFindAllCd2() {
        $rows = $this->service->findAllCd();
        $this->assertEquals('S2ZF', $rows[0]['CONTENT']);
    }

    public function setUp() {
        s2app::init();
        s2component('Service_SampleService2')->setName('service2');
        $this->service = s2app::get('service2');
    }

    public function tearDown() {
        $this->service = null;
    }
}


