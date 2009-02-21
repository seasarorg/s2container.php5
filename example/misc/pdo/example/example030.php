<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_a.db';

class FooDto extends StandardDto{}
class BarDto extends StandardDto{}

interface IDao {
    /**
     * @S2Pdo('FooDto')
     */
    public function findById($id);

    /**
     * @S2Pdo('dto' => 'BarDto')
     */
    public function findByArg2($id, $ename);

}

s2component('IDao');
s2app::registerAspect('pdo.interceptor', '/Dao$/');


$dao = s2app::get('IDao');

$rows = $dao->findById(7369);

$rows = $dao->findByArg2(7369, 'SMITH');
var_dump($rows);



