<?php
require_once dirname(dirname(dirname(__FILE__))) . '/example.inc.php';
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
require_once "Spyc.php";
require_once "Hello2.class.php";

$PATH = EXAMPLE_DIR . "/aop/intertype/SerializableInterType.yml";
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello2');

$hello->unserialize('a:3:{s:4:"hoge";s:5:"Hello";s:3:"foo";s:7:"World!!";s:3:"bar";s:12:"HelloWorld!!";}');

echo $hello->getHoge() . PHP_EOL;
echo $hello->getFoo() . PHP_EOL;
echo $hello->getBar() . PHP_EOL;

echo $hello->serialize() . PHP_EOL;

?>
