<?php
interface IEmpDao {
    const BEAN = "EmpDto";
    
    function findEmpByEname($ename);
    function findEmpByEnameArray($nameArray);
    function findEmpByEnameObject($empDto);

    function findEmpByEnameQuery($ename);
    const findEmpByEnameQuery_QUERY = 'select * from emp where ename = \'{$ename}\';';

    function findEmpByEnameFile($ename);
    const findEmpByEnameFile_FILE = "%TEST_DIR%/sample/db/IEmpDao_findEmpByEnameFile.txt";
}
?>