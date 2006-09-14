<?php
require_once dirname(dirname(__FILE__)) . '/example.inc.php';
require_once 'ArgExp.class.php';

$PATH = YAML_DICON_DIR . "/expression/ArgExp.yml";
		
$container = S2ContainerFactory::create($PATH);
$sample = $container->getComponent('sample1');
$sample->showValue();
$sample->showMessage();
$sample = $container->getComponent('sample2');
$sample->showValue();
$sample->showMessage();

?>