<?php
/**
 * @S2Component('name' => 'act')
 */
class Action{
    public $service = null;
    private $dao = null;
    public function setDao(Dao $dao) {
        $this->dao = $dao;
    }
    public function indexAction() {
        $result = $this->service->add(1, 2);
    }
    public function getById() {
        return $this->dao->findById(10);
    }
}
