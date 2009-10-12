<?php
use \seasar\container\S2ApplicationContext as s2app;

class Service_CustomerServiceTest extends PHPUnit_Framework_TestCase {

    public function testFindAllCustomer() {
        $rows = $this->service->findAllCustomer();
        $this->assertTrue($rows instanceof Zend_Db_Table_RowSet);
    }

    public function setUp() {
        s2app::init();
        require(APPLICATION_PATH . '/dicons/index/customer-list.php');
        $this->service = s2app::get('customer');
    }

    public function tearDown() {
        $this->service = null;
    }
}


