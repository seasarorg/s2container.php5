<?php
interface DeptDao {
	
	const findDeptByDeptno_QUERY = 'select * from dept where deptno = \'{$deptno}\'';
    function findDeptByDeptno($deptno);
}
?>