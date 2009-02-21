<?php
define('ROOT_DIR', dirname(dirname(__FILE__)));
require_once(dirname(dirname(ROOT_DIR)) . '/example.inc.php');

use \seasar\container\S2ApplicationContext as s2app;
s2app::import(ROOT_DIR . '/classes');
s2app::import(ROOT_DIR . '/example/EmpDao2.php');

StandardPdo::$DSN = 'sqlite:' . ROOT_DIR . '/example/db/sqlite_a.db';

s2aspect('pdo.interceptor', '/Dao2$/', '/^find/');

$dao = s2app::get('EmpDao2');
$paginate = new Paginate;
$paginate->setLimit(2);
$rows = $dao->byPaginate($paginate);
var_dump($rows);

$paginate->next();
$rows = $dao->byPaginate($paginate);
var_dump($rows);

$dao = s2app::get('EmpDao2');
$paginate = new Paginate;
$paginate->setLimit(2);
$rows = $dao->byPaginate($paginate);
var_dump($rows);

$paginate->next();
$rows = $dao->byPaginate($paginate);
var_dump($rows);


