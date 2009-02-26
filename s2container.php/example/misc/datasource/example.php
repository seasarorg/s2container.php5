<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
define('DB_DIR', dirname(__FILE__) . '/db');
use seasar\container\S2ApplicationContext as s2app;

s2app::import(dirname(__FILE__) . '/classes');
$cdDao = s2app::get('CdDao');
var_dump($cdDao->findAll());

s2app::init();
s2app::import(dirname(__FILE__) . '/classes/CdDao.php');
s2app::import(dirname(__FILE__) . '/dicon');
$cdDao = s2app::get('CdDao');
var_dump($cdDao->findAll());


