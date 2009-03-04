<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_a.db';

/**
 * @S2Aspect('pdo.interceptor')
 */
interface IDao {
    public function findAll();

    public function findById($id);

    public function findByArg2($id, $ename);

    public function findByArg3($id, $ename, $sample);
}

s2component('IDao');
//s2aspect('pdo.interceptor', '/Dao$/');


$dao = s2app::get('IDao');
$rows = $dao->findAll();

$rows = $dao->findById(7369);

$rows = $dao->findByArg2(7369, 'SMITH');

$rows = $dao->findByArg3(7369, 'SMITH', 1000);
var_dump($rows);


