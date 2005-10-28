<?php
interface BeanDeptDao {
	
	const BEAN = "DeptDto";
    function findDeptByDeptno($deptno);
}
?>