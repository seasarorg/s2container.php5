<?php
class MySQLDeptDaoImpl implements IDeptDao {
    private $session;
    
    function MySQLDeptDaoImpl() {
    }
    
    function setSession(DBSession $session){
    	$this->session = $session;
    }
    
    function findDeptByDeptno($no){
        $this->session->connect();
        $db = $this->session->getConnection();
        $result = mysql_query("select * from dept where deptno = $no;",$db); 
        $rows = mysql_fetch_array($result,MYSQL_NUM);
        $this->session->disconnect();
        return $rows;
    }

    function findDeptByName($name){
        $db = $this->session->getConnection();
        $sql = 'select * from dept where dname = \'' . $name . '\';';
        $result = mysql_query($sql,$db); 
        if(!$result){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
        	throw new Exception();
        }
        $rows = mysql_fetch_array($result,MYSQL_NUM);
        return $rows;
    }

    function updateDnameByDeptno($deptno,$dname){
        $db = $this->session->getConnection();
        $sql = 'update dept set dname = \'' . $dname . 
               '\' where deptno = \'' . $deptno . '\';';
        $result = mysql_query($sql,$db); ; 
        if(!$result){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
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