<?php
use \seasar\container\S2ApplicationContext as s2app;

class Model_DbTable_AccountsTest extends PHPUnit_Framework_TestCase {

    public function testFetchAll() {
        $this->assertEquals(5, count($this->model->fetchAll()));
    }

    public function setUp() {
        $this->model   = s2app::get('Model_DbTable_Accounts');
    }

    public function tearDown() {
        $this->model = null;
    }
}


