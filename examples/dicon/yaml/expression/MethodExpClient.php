<?php
require_once dirname(dirname(__FILE__)) . '/example.inc.php';
require_once 'MethodExp.class.php'; 

$PATH = YAML_DICON_DIR . "/expression/MethodExp.yml";
		
$container = S2ContainerFactory::create($PATH);
$method = $container->getComponent('method');

?>
