<?php
define('ROOT_DIR', dirname(__FILE__));
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');

// PdoInterceptor��PDO�R���|�[�l���g�𓯂�namespace�ɓo�^����B
s2component('PdoInterceptor')->setName('interceptor');
s2app::import(ROOT_DIR . '/config/SqliteAPdo.php');

s2aspect('interceptor', '/Dao$/');

$dao = s2app::get('sample\pdo\CdDao');
$condition = new StdClass;
$condition->id = 1;
$rows = $dao->findBySqlFile1($condition);
var_dump($rows);
