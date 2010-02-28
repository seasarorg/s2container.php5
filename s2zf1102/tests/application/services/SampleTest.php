<?php
class Service_SampleTest extends PHPUnit_Framework_TestCase {

    public function testFetchAllBugs() {
        $this->assertEquals(3, count($this->service->fetchAllBugs()));
    }

    public function testFetchAllBugsWithAccounts() {
        $rows = $this->service->fetchProductBugDescriptions();
        $this->assertEquals(6, count($rows));
        $this->assertEquals(2, count($rows[0]));
        $this->assertTrue(isset($rows[0]['bug_description']));
        $this->assertTrue(isset($rows[0]['product_name']));
    }

    public function setUp() {
        require(APPLICATION_PATH . '/dicons/dicon.php');
        $this->service = s2get('Service_Sample');
    }

    public function tearDown() {
        $this->service = null;
    }
}


