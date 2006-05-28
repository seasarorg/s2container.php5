<?php
interface IDeptDao {

    function findDeptByDeptno($no);
    
    function findDeptByName($name);
    
    function insertDept($deptArray);
    function updateDnameByDeptno($deptno,$dname);
}
?>