<?php
class S2ContainerClassLoaderTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testImport() {
       
        print __METHOD__ . "\n";

        S2ContainerClassLoader::import('D:/tmp');
        $this->assertEqual(S2ContainerClassLoader::$USER_CLASSES,
                           array());

        S2ContainerClassLoader::import('/hogehoge');
        $this->assertEqual(S2ContainerClassLoader::$USER_CLASSES,
                           array());

        S2ContainerClassLoader::import(__FILE__);
        $this->assertEqual(S2ContainerClassLoader::$USER_CLASSES,
                           array('S2ContainerClassLoaderTests'=>__FILE__));

        S2ContainerClassLoader::import(dirname(__FILE__));
        $arr = S2ContainerClassLoader::$USER_CLASSES;
        $this->assertEqual(4,count($arr));

        print "\n";
    } 
}
?>