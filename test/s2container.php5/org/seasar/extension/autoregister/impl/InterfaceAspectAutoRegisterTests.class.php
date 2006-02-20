<?php
class InterfaceAspectAutoRegisterTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $register = new S2Container_InterfaceAspectAutoRegister();
        $this->assertIsA($register,'S2Container_InterfaceAspectAutoRegister');

        print "\n";
    }
    
    function testRegisterAll() {
        print __METHOD__ . "\n";

        $path = dirname(__FILE__) . 
                '/sample4/a_InterfaceAspectAutoRegister.dicon';
        $container = S2ContainerFactory::create($path);
        $container->init();
        $a = $container->getComponent('a');
        $a->test();
        $this->assertIsA($a,'A_InterfaceAspectAutoRegisterEnhancedByS2AOP');

        $b = $container->getComponent('b');
        $this->assertIsA($b,'B_InterfaceAspectAutoRegister');

        print "\n";
    }    
}

class A_InterfaceAspectAutoRegister 
    implements IA_InterfaceAspectAutoRegister{
    public function test(){
        print __METHOD__ . "\n";  
    }   
}

interface IA_InterfaceAspectAutoRegister {
    public function test();
}

class B_InterfaceAspectAutoRegister {
    public function test(){
        print __METHOD__ . "\n";  
    }   
}
?>
