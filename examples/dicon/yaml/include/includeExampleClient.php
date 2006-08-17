<?php
require_once dirname(dirname(__FILE__)) . '/example.inc.php';
require_once 'Hello.class.php';
require_once 'HelloClient.class.php';
require_once 'HelloImpl.class.php';
require_once 'RootHelloClient.class.php';
require_once 'AaaHelloClient.class.php';
require_once 'BbbHelloClient.class.php';

$PATH = YAML_DICON_DIR . "/include/root.yml";
		
$container = S2ContainerFactory::create($PATH);
$hello = $container->getComponent('root');
$hello->showMessage();
$hello = $container->getComponent('aaa');
$hello->showMessage();
$hello = $container->getComponent('bbb');
$hello->showMessage();

?>