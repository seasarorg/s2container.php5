<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

s2component('PDO')
  ->setConstructClosure(function(){
      return new PDO('sqlite:sqlite.db');
  });


require_once('Zend/Db/Adapter/Abstract.php');
require_once('Zend/Db.php');
require_once('Zend/Db/Table/Abstract.php');
$options = array('dbname' => 'sqlite.db');
s2component('Zend_Db_Adapter_Abstract')
  ->setName('zendPdo')
  ->setConstructClosure(function() use($options) {
        $db = Zend_Db::factory('PDO_SQLITE', $options);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        return $db;
    });

$pdo = s2app::get('PDO');
echo get_class($pdo) . PHP_EOL;

$pdo = s2app::get('zendPdo');
$pdo->getConnection();
echo get_class($pdo) . PHP_EOL;
