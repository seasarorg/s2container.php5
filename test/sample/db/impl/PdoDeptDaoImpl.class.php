<?php
class PdoDeptDaoImpl implements IDeptDao {
    private $session;
    
    function PdoDeptDaoImpl() {
    }
    
    function setSession(S2Container_DBSession $session){
    	$this->session = $session;
    }
    
    function findDeptByDeptno($no){
        $this->session->connect();
        $db = $this->session->getConnection();
        $result = $db->query("select * from dept where deptno = $no;"); 
        $rows = $result->fetch(PDO::FETCH_BOUND);
        $this->session->disconnect();
        return $rows;
    }

    function findDeptByName($name){
        $db = $this->session->getConnection();
        $sql = 'select * from dept where dname = \'' . $name . '\';';
        $result = $db->query($sql); 
        $rows = $result->fetch(PDO::FETCH_BOUND);
        return $rows;
    }

    function updateDnameByDeptno($deptno,$dname){
        $db = $this->session->getConnection();
        $sql = 'update dept set dname = \'' . $dname . 
               '\' where deptno = \'' . $deptno . '\';';
        $result = $db->query($sql); 
        print "something happen.\n";
        throw new Exception();
        return null;
    }
    
    function insertDept($deptArray){}
}
?>