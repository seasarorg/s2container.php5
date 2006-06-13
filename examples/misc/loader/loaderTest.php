<?php
error_reporting(E_ALL | E_STRICT);

require_once(dirname(dirname(dirname(__FILE__))) . '/example.inc.php'); 

S2ContainerClassLoader::import('A.class.php');
S2ContainerClassLoader::import('B.class.php','B');
S2ContainerClassLoader::import('C.test.class.php');
S2ContainerClassLoader::import('classes_dir2/G.class.php');
S2ContainerClassLoader::import('classes_dir');
S2ContainerClassLoader::import('no-exist-classes-dir');

$classes = array(
           'H'=>'./H.class.php',
           'I'=>dirname(__FILE__).'/I.class.php');
S2ContainerClassLoader::import($classes);
S2ContainerClassLoader::import('jk.classes.php','J');
S2ContainerClassLoader::import('jk.classes.php','K');

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
$obj = new H();
print get_class($obj)."\n";
$obj = new I();
print get_class($obj)."\n";
$obj = new J();
print get_class($obj)."\n";
$obj = new K();
print get_class($obj)."\n";
?>
