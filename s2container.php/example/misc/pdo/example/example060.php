<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/example/config/pdo.dicon');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_a.db';

interface IDao {
    public function findById($id);
}

class Dao {
    public function findById($id) {
        return 'select * from emp where EMPNO = :id';
    }
}


s2component('IDao');
s2aspect('pdo.interceptor', '/^IDao$/');

s2component('Dao');
s2aspect('pdo_c.interceptor', '/^Dao$/');

$dao = s2app::get('Dao');
$rows = $dao->findById(7369);
var_dump($rows);

$dao = s2app::get('IDao');
$rows = $dao->findById(7369);
var_dump($rows);

