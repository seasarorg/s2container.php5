<?php
require_once('S2Erd.php');

use seasar\container\S2ApplicationContext as s2app;
s2app::init();

require_once(dirname(__FILE__) . '/s2erd.php');
s2component('seasar\erd\writer\DynamicWriter');
s2component('seasar\erd\util\zend\Autoloader');

Zend_Loader_Autoloader::getInstance()->unshiftAutoloader(s2app::get('seasar\erd\util\zend\Autoloader'));

