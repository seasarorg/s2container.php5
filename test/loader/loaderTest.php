<?php
define('HOME_DIR',dirname(dirname(dirname(__FILE__))));

require_once(HOME_DIR . '/s2container.inc.php'); 
function __autoload($class=null){
    if(S2ContainerClassLoader::load($class)){return;}
}

S2ContainerClassLoader::import('A.class.php');
S2ContainerClassLoader::import('B.class.php','B');
S2ContainerClassLoader::import('C.test.class.php');
S2ContainerClassLoader::import('classes_dir2/G.class.php');
S2ContainerClassLoader::import('classes_dir');
S2ContainerClassLoader::import('no-exist-classes-dir');

print_r(S2ContainerClassLoader::$USER_CLASSES);
$obj = new A();
print get_class($obj)."\n";
$obj = new B();
print get_class($obj)."\n";
$obj = new C();
print get_class($obj)."\n";
$obj = new D();
print get_class($obj)."\n";
$obj = new E();
print get_class($obj)."\n";
$obj = new G();
print get_class($obj)."\n";
?>
