<?php
require_once dirname(dirname(dirname(__FILE__))) . '/example.inc.php';
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
require_once "Spyc.php";
require_once "MyClass.class.php";
require_once "MyInterType.class.php";

$PATH = EXAMPLE_DIR . "/aop/intertype/MyInterType.yml";
$container = S2ContainerFactory::create($PATH);
$class = $container->getComponent('MyClass');

$class->hello();
$class->public_ = __FILE__;
$class->set('aaa', 123, 'bbb');

?>
