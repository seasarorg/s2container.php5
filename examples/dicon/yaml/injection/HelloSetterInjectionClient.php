<?php
require_once dirname(dirname(__FILE__)) . '/example.inc.php';
require_once 'Hello.class.php';
require_once 'HelloSetterInjection.class.php';
$PATH = YAML_DICON_DIR . "/injection/HelloSetterInjection.dicon.yml";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('Hello');
$hello->showMessage();

?>
