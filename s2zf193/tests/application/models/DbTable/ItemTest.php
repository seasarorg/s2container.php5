<?php
class Model_DbTable_ItemTest extends PHPUnit_Framework_TestCase {

    public function testFind() {
        $id = 10;
        $row = $this->itemModel->find($id)->current();
        $this->assertEquals(10, $row->item_id);
        $this->assertEquals('みかん', $row->item_name);
    }

    public function testDependent() {
        $id = 10;
        $row = $this->itemModel->find($id)->current();
        $rows = $row->findModel_DbTable_OrderingDetail()->toArray();
        $this->assertEquals(2, count($rows));
    }

    public function testParent() {
        $id = 10;
        $row = $this->itemModel->find($id)->current();
        $row = $row->findModel_DbTable_OrderingDetail()->current();
        $row = $row->findParentModel_DbTable_OrderingByOrderingId();
        $this->assertEquals(10, $row->customer_id);

        $row = $row->findParentModel_DbTable_Customer();
        $this->assertEquals('くまさん', $row->customer_name);
    }

    public function testInsert() {
        
        $adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        try {
            $adapter->beginTransaction();

            $row = $this->itemModel->createRow();
            $row->item_id = 31;
            $row->item_name = 'とまと';
            $row->save();
            $this->assertEquals(31, $row->item_id);
            $adapter->rollback();
        } catch(Exception $e) {
            $adapter->rollback();
            throw $e;
        }
    }

    public function testFilterInput() {
//        $filters = $this->itemModel->getFilters();
//        $validators = $this->itemModel->getValidators();

        $filterInput = $this->itemModel->getFilterInput();

        $id = 10;
        $row = $this->itemModel->find($id)->current();
        $filterInput->setData($row->toArray());
        $this->assertTrue($filterInput->isValid());

        $row->item_name = null;
        $filterInput->setData($row->toArray());
        $this->assertFalse($filterInput->isValid());
        $messages = $filterInput->getMessages();
        $this->assertTrue(isset($messages['item_name']['isEmpty']));

        $row->item_name = str_repeat('あ', 65);
        $filterInput->setData($row->toArray());
        $this->assertFalse($filterInput->isValid());
        $messages = $filterInput->getMessages();
        $this->assertTrue(isset($messages['item_name']['stringLengthTooLong']));

        $row->item_name = ' abc ';
        $filterInput->setData($row->toArray());
        $this->assertTrue($filterInput->isValid());
        $this->assertEquals('abc', $filterInput->item_name);
    }

    public function setUp() {
        $this->itemModel = new Model_DbTable_Item;
    }

    public function tearDown() {
        $this->itemModel = null;
    }
}


