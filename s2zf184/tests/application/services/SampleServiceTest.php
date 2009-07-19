<?php
use \seasar\container\S2ApplicationContext as s2app;

class Service_SampleServiceTest extends PHPUnit_Framework_TestCase {

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
        $this->service = s2app::get('Service_SampleService');
    }

    public function tearDown() {
        $this->service = null;
    }
}


