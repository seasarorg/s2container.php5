<?php
class Service_Sample {

    public function setModel(Model_DbTable_Bugs $model) {
        $this->model = $model;
    }

    public function setAdapter(Zend_Db_Adapter_Abstract $adapter) {
        $this->adapter = $adapter;
    }
    
    public function fetchAllBugs() {
        return $this->model->fetchAll()->toArray();
    }

    public function fetchProductBugDescriptions() {
        $select = $this->adapter->select()->from(array('bp' => Model_DbTable_BugsProducts::PNAME), array());
        $select->joinLeft(array('b' => Model_DbTable_Bugs::PNAME), 'b.bug_id = bp.bug_id', array('bug_description'));
        $select->joinLeft(array('p' => Model_DbTable_Products::PNAME), 'p.product_id = bp.product_id', array('product_name'));
        return $this->adapter->fetchAll($select);
    }
}

