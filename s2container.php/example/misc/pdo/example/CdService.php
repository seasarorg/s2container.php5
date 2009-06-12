<?php
class CdService {

    private $dao = null;
    public function setCdDao(CdDao $dao) {
        $this->dao = $dao;
    }

    public function execute() {
        $this->dao->insert(10, 'aaa', 'bbb');
        $this->dao->insert(10, 'aaa', 'bbb');
    }
}
