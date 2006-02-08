<?php
class AspectAutoRegisterTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $register = new S2Container_AspectAutoRegister();
        $this->assertIsA($register,'S2Container_AspectAutoRegister');

        print "\n";
    }
    
    function testRegisterAll() {
        print __METHOD__ . "\n";

        $path = dirname(__FILE__) . 
                '/sample/a_AspectAutoRegister.dicon';
        $container = S2ContainerFactory::create($path);
        $container->init();
        $a = $container->getComponent('a');
        $a->test();
        $this->assertIsA($a,'S2Container_DefaultAopProxy');

        $b = $container->getComponent('b');
        $this->assertIsA($b,'B_AspectAutoRegisterTests');

        $c = $container->getComponent('c');
        $this->assertIsA($c,'C_A_spectAutoRegisterTests');

        $d = $container->getComponent('d');
        $this->assertIsA($d,'S2Container_DefaultAopProxy');

        print "\n";
    }    
}

class A_AspectAutoRegisterTests {
    public function test(){
        print __METHOD__ . " called.\n";
    }   
}

class B_AspectAutoRegisterTests {
    public function test(){
        print __METHOD__ . " called.\n";
    }   
}

class C_A_spectAutoRegisterTests {
    public function test(){
        print __METHOD__ . " called.\n";
    }   
}

class D_AspectAutoRegisterTests {
    public function test(){
        print __METHOD__ . " called.\n";
    }   
}
?>
