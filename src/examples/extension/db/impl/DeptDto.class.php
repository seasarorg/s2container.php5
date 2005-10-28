<?php

class DeptDto {

    private $deptno;
    private $dname;
    
    function DeptDto() {}
    
    function setDeptno($deptno){
    	$this->deptno = $deptno;
    }
    function getDeptno(){
        return $this->deptno;	
    }
    
    function setDname($dname){
    	$this->dname = $dname;
    }
    function getDname(){
        return $this->dname;
    }

    function __toString(){
    	$str = "deptno = " . $this->deptno . ", dname = " . $this->dname;
        return $str;	
    }
}
?>