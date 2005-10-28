<?php
class TxDeptDaoImpl implements DeptDao {

    private $session;

    function TxDeptDaoImpl(S2Container_DBSession $session) {
        $this->session = $session;
    }
    
    function findDeptByDeptno($deptno){
        $db = $this->session->getConnection();
        $result = $db->query("select * from dept where deptno = '{$deptno}';"); 
        $row = $result->fetchRow();

        return $row;
    }

}
?>