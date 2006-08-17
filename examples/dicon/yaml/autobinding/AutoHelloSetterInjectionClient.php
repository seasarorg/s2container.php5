<?php
require_once dirname(dirname(__FILE__)) . '/example.inc.php';
require_once 'Hello.class.php';
require_once 'Map.class.php';
require_once 'HashMap.class.php';
require_once 'AutoHelloSetterInjection.class.php';

$PATH = YAML_DICON_DIR . "/autobinding/AutoHelloSetterInjection.yml";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();

?>