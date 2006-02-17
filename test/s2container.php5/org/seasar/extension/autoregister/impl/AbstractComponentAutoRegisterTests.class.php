<?php
class AbstractComponentAutoRegisterTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $this->assertIsA($register,'S2Container_AbstractAutoRegister');
        print "\n";
    }

    function testHasComponentDef() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $register->setContainer(new S2ContainerImpl());
        $this->assertEqual($register->hasComponentDef('Test'),null);
        print "\n";
    }
}
class Test_AbstractComponentAutoRegisterTests
    extends S2Container_AbstractComponentAutoRegister{

    public function registerAll(){
        
    }
    
    public function isIgnore($name){
        return parent::isIgnore($name);    
    }

    public function hasComponentDef($name){
        return parent::hasComponentDef($name);    
    }
    
    public function __construct(){
        parent::__construct();   
    }        
}
?>
