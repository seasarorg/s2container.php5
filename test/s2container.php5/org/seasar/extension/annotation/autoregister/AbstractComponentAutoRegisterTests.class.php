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

    function testIsIgnore() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractComponentAutoRegisterTests();
        $register->addIgnoreClassPattern(dirname(__FILE__),"Foo,Bar");
        $this->assertEqual($register->isIgnore('d:/tmp','Foo'),true);
        $this->assertEqual($register->isIgnore('d:/tmp','Bar'),true);
        $this->assertEqual($register->isIgnore('d:/tmp','Hoge'),false);
        print "\n";
    }

}
class Test_AbstractComponentAutoRegisterTests
    extends S2Container_AbstractComponentAutoRegister{

    public function registerAll(){
        
    }
    
    public function isIgnore($path,$name){
        return parent::isIgnore($path,$name);    
    }

    public function hasComponentDef($name){
        return parent::hasComponentDef($name);    
    }
    
    public function __construct(){
        parent::__construct();   
    }        
}
?>
