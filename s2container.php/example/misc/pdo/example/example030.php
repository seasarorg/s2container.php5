<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_a.db';

class FooDto extends StandardDto{}
class BarDto extends StandardDto{}

interface IDao {

    const findById_DTO = 'FooDto';
    public function findById($id);

    /**
     * @DTO('BarDto')
     */
    public function findByArg2($id, $ename);

}

s2component('IDao');
s2aspect('pdo.interceptor', '/Dao$/');


$dao = s2app::get('IDao');

$rows = $dao->findById(7369);

$rows = $dao->findByArg2(7369, 'SMITH');
var_dump($rows);



