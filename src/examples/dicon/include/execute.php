<?php
require_once(dirname(__FILE__) . '/example.inc.php');
$PATH = EXAMPLE_DIR . "/dicon/include/root.dicon";
$PATH = EXAMPLE_DIR . "/dicon/include/root.ini";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('root');
$hello->showMessage();
$hello = $container->getComponent('aaa');
$hello->showMessage();
$hello = $container->getComponent('bbb');
$hello->showMessage();

?>