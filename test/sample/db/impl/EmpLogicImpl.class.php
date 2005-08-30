<?php

class EmpLogicImpl implements IEmpLogic {
    private $dao;
    
    function EmpLogicImpl(IEmpDao $dao) {
    	$this->dao = $dao;
    }
    
    function getEmpByEname($ename){
    	return $this->dao->findEmpByEname($ename);
    }
}
?>