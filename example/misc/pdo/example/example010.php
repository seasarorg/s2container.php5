<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_a.db';

class Dao {
    public function findAll() {
        return 'select * from emp';
    }

    public function findById($id) {
        return 'select * from emp where EMPNO = :id';
    }

    public function findByArg2($id, $ename) {
        return 'select * from emp where EMPNO = :id and ENAME = :ename';
    }

    public function findByArg3($id, $ename, $job) {
        return 'select * from emp where EMPNO = :id and ENAME = :ename and JOB = :job';
    }

    public function findByArg4($id, $ename, $job, $sample) {
        return 'select * from emp where EMPNO = :id and ENAME = :ename and JOB = :job';
    }

    public function findByXxx($id, $ename) {
        return 'select * from emp where EMPNO = :id and ENAME = :ename and JOB = :job';
    }

    public function findByIdWithFile($id) {
        return array('id' => $id);
    }

    public function findByWithFileArg2($id, $ename) {
        return array('id' => $id, 'ename' => $ename);
    }

    public function findByConditionWithFile($id) {
        return array('id' => $id);
    }

    public function findByIdWithArray1() {
        $sql = 'select * from emp where EMPNO = :id';
        $context = array('id' => 7369);
        return array($sql, $context);
    }

    public function findByIdWithArray2() {
        $sql = 'select * from emp where EMPNO = :id';
        $context = array('id' => 7369, 'ename' => 'SMITH');
        return array($sql, $context);
    }

    public function findByIdWithArray3($id) {
        $sql = 'select * from emp where EMPNO = :id';
        $context = array('id' => 7369);
        return array($sql, $context);
    }

    public function findByIdWithArray4() {
        $sql = 'select * from emp where EMPNO = :id';
        $context = array('id' => 7369, 'sample' => 1000);
        return array($sql, $context);
    }

}

s2component('Dao');
s2aspect('pdo.interceptor', '/Dao$/');


$dao = s2app::get('Dao');
$rows = $dao->findAll();

$rows = $dao->findById(7369);

$rows = $dao->findByArg2(7369, 'SMITH');

$rows = $dao->findByArg3(7369, 'SMITH', 'CLERK');

$rows = $dao->findByArg4(7369, 'SMITH', 'CLERK', 1000);

$rows = $dao->findByXxx(7369, 'SMITH');

$rows = $dao->findByIdWithFile(7369);

$rows = $dao->findByWithFileArg2(7369, 'SMITH');

$rows = $dao->findByConditionWithFile(7369);

$rows = $dao->findByIdWithArray1();

$rows = $dao->findByIdWithArray2();

$rows = $dao->findByIdWithArray3(1000);

$rows = $dao->findByIdWithArray4();

var_dump($rows);


