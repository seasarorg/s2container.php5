<?php
class SingletonS2ContainerFactoryTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

/*
    function testGetConfigPath() {
       
        print __METHOD__ . "\n";
       
        $this->assertEqual(S2Container_SingletonS2ContainerFactory::getConfigPath(),
                           "/path/to/app.dicon");
        
        print "\n";
    }
*/

    function testInit() {
       
        print __METHOD__ . "\n";
       
        S2Container_SingletonS2ContainerFactory::init();
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A');
        
        print "\n";
    }

    function testInitWithPath() {
       
        print __METHOD__ . "\n";

        $path = TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test1.dicon';    
        S2Container_SingletonS2ContainerFactory::init($path);
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A');

        $b = $container->getComponent('test1.b');
        $this->assertIsA($b,'A');
                
        print "\n";
    }

    function testInitWithPathNotExist() {
       
        print __METHOD__ . "\n";

        $path = TEST_DIR . '/test1.dicon';  
        try{  
            S2Container_SingletonS2ContainerFactory::init($path);
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_S2RuntimeException');	
            print $e->getMessage() ."\n";
        }    
        print "\n";
    }
}
?>