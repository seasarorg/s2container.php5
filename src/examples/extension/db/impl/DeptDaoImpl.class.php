<?php
class DeptDaoImpl implements DeptDao {

    private $dataSource;

    function DeptDaoImpl(DataSource $dataSource) {
        $this->dataSource = $dataSource;
    }
    
    function findDeptByDeptno($deptno){
        $db = $this->dataSource->getConnection();
        $result = $db->query("select * from dept where deptno = '{$deptno}';"); 
        $row = $result->fetchRow();
        //$db->disconnect();
        $this->dataSource->disconnect($db);
        return $row;
    }

}
?>