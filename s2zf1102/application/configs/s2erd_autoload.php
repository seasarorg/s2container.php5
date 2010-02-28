<?php
require_once('S2Erd.php');

s2init();

require_once(dirname(__FILE__) . '/s2erd.php');
s2component('seasar\erd\writer\DynamicWriter');
s2component('seasar\erd\util\zend\Autoloader');

Zend_Loader_Autoloader::getInstance()->unshiftAutoloader(s2get('seasar\erd\util\zend\Autoloader'));

