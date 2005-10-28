<?php
class ADOdbDeptDaoImpl implements IDeptDao {
    private $session;
    
    function ADOdbDeptDaoImpl() {
    }
    
    function setSession(S2Container_DBSession $session){
    	$this->session = $session;
    }
    
    function findDeptByDeptno($no){
        $this->session->connect();
        $db = $this->session->getConnection();
        $db->SetFetchMode(ADODB_FETCH_NUM);
        $result = $db->query("select * from dept where deptno = $no;"); 
        $rows = $result->fetchRow();
        $this->session->disconnect();
        return $rows;
    }

    function findDeptByName($name){
        $db = $this->session->getConnection();
        $db->SetFetchMode(ADODB_FETCH_NUM);
        $sql = 'select * from dept where dname = \'' . $name . '\';';
        $result = $db->query($sql); 
        if(DB::isError($result)){
        	print $result->getMessage();
        	print $result->getDebugInfo();
        	throw new Exception();
        }
        $rows = $result->fetchRow();
        return $rows;
    }

    function updateDnameByDeptno($deptno,$dname){
        $db = $this->session->getConnection();
        $db->SetFetchMode(ADODB_FETCH_NUM);
        $sql = 'update dept set dname = \'' . $dname . 
               '\' where deptno = \'' . $deptno . '\';';
        $result = $db->query($sql); 
        if(DB::isError($result)){
        	print $result->getMessage();
        	print $result->getDebugInfo();
        	throw new Exception();
        }
        print "something happen.\n";
        throw new Exception();
        return null;
    }
    
    function insertDept($deptArray){}
}
?>