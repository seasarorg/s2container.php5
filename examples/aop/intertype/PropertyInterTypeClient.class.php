<?php
require_once dirname(dirname(dirname(__FILE__))) . '/example.inc.php';
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
require_once "Spyc.php";
require_once "Hello.class.php";

$PATH = EXAMPLE_DIR . "/aop/intertype/PropertyInterType.yml";
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');

$hello->setHoge("Hello");
$hello->setFoo("World!!");
$hello->setBar($hello->getHoge() . $hello->getFoo());

echo $hello->getHoge() . PHP_EOL;
echo $hello->getFoo() . PHP_EOL;
echo $hello->getBar() . PHP_EOL;

?>
