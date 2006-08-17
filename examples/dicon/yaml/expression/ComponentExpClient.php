<?php
require_once dirname(dirname(__FILE__)) . '/example.inc.php';
require_once 'ComponentExp.class.php';        

$PATH = YAML_DICON_DIR . "/expression/ComponentExp.yml";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('hello');
$hello->showMessageA();
$hello->showMessageB();
$hello = $container->getComponent('hello2');
$hello->showMessageA();
$hello->showMessageB();
$hello = $container->getComponent('hello3');
$hello->showMessageA();
$hello->showMessageB();

?>