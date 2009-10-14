<?php
use seasar\container\S2ApplicationContext as s2app;

require_once(dirname(APPLICATION_PATH) . '/library/Seasar/A5.php');

s2app::register('Seasar_A5_Parser');
s2app::register('Seasar_A5_Generator_MySQL');
s2app::register('Seasar_A5_Writer_Dynamic');
s2app::register('Seasar_A5_Autoloader')->setConstructClosure(function() {
    $loader = new Seasar_A5_Autoloader;
    $loader->setA5erFile(dirname(APPLICATION_PATH) . '/var/db/project.a5er');
    return $loader;
});

Zend_Loader_Autoloader::getInstance()->unshiftAutoloader(
    \seasar\container\S2ApplicationContext::create()->getComponent('Seasar_A5_Autoloader'));

