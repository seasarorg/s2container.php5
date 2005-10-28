<?php
class PostgresDeptDaoImpl implements IDeptDao {
    private $session;
    
    function PostgresDeptDaoImpl() {
    }
    
    function setSession(S2Container_DBSession $session){
    	$this->session = $session;
    }
    
    function findDeptByDeptno($no){
        $this->session->connect();
        $db = $this->session->getConnection();
        $result = pg_query($db,"select * from dept where deptno = $no;"); 
        $rows = pg_fetch_row($result);
        $this->session->disconnect();
        return $rows;
    }

    function findDeptByName($name){
        $db = $this->session->getConnection();
        $sql = 'select * from dept where dname = \'' . $name . '\';';
        $result = pg_query($db,$sql); 
        if(!$result){
    		$this->log_->error(pg_result_error($db),
    		                   __METHOD__);
        	throw new Exception();
        }
        $rows = pg_fetch_row($result);
        return $rows;
    }

    function updateDnameByDeptno($deptno,$dname){
        $db = $this->session->getConnection();
        $sql = 'update dept set dname = \'' . $dname . 
               '\' where deptno = \'' . $deptno . '\';';
        $result = pg_query($db,$sql); ; 
        if(!$result){
    		$this->log_->error(pg_result_error($db),
    		                   __METHOD__);
          	throw new Exception();
        }
        print "something happen.\n";
        throw new Exception();
        return null;
    }
    
    function insertDept($deptArray){}
}
?>