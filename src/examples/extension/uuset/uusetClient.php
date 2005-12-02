<?php
require_once(dirname(__FILE__) . '/uuset.inc.php');
$PATH = EXAMPLE_DIR . "/extension/uuset/uuset.dicon";
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('HelloImpl');
$hello->showMessage();
?>