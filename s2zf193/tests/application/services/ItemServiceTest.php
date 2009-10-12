<?php
use \seasar\container\S2ApplicationContext as s2app;

class Service_ItemServiceTest extends PHPUnit_Framework_TestCase {

    public function testFindAllItem() {
        $rows = $this->service->findAllItem();
        $this->assertTrue($rows instanceof Zend_Db_Table_RowSet);
    }

    public function testFindOrderingByItemId() {
        $id = 10;
        $rows = $this->service->findOrderingByItemId($id);
        $this->assertTrue(is_array($rows));
        $this->assertEquals(2, count($rows));
    }

    public function setUp() {
        s2app::init();
        $this->service = s2app::get('Service_ItemService');
    }

    public function tearDown() {
        $this->service = null;
    }
}


