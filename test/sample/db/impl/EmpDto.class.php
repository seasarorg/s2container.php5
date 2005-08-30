<?php

class EmpDto {
    private $empno;
    private $ename;
    private $job;
    private $mgr;
    
    function EmpDto() {
    }
    
    function setEmpno($empno){
        $this->empno = $empno;    	
    }
    function getEmpno(){
        return $this->empno;	
    }

    function setEname($ename){
        $this->ename = $ename;    	
    }
    function getEname(){
        return $this->ename;	
    }

    function setJob($job){
        $this->job = $job;    	
    }
    function getJob(){
        return $this->job;	
    }

    function setMgr($mgr){
        $this->mgr = $mgr;    	
    }
    function getMgr(){
        return $this->mgr;	
    }

}
?>