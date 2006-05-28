<?php
require_once(dirname(__FILE__) . '/mock.inc.php');
$PATH = EXAMPLE_DIR . "/aop/mockinterceptor/Hello.dicon";
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
print $hello->greeting() . "\n";
print $hello->echoo("echo test.") . "\n";
?>