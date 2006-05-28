<?php
class AbstractAutoRegisterTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstantiate() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractAutoRegisterTests();
        $this->assertIsA($register,'S2Container_AbstractAutoRegister');
        print "\n";
    }

    function testHasComponentDef() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractAutoRegisterTests();
        $register->setContainer(new S2ContainerImpl());
        $this->assertEqual($register->hasComponentDef('Test'),null);
        print "\n";
    }

    function testFindComponentDef() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractAutoRegisterTests();
        $container = new S2ContainerImpl();
        $container->register(new S2Container_ComponentDefImpl(
                             'A_AbstractAutoRegisterTests',
                             'a'));
        $register->setContainer($container);
        $cd = $register->findComponentDef('a');
        $this->assertIsA($cd,'S2Container_ComponentDefImpl');
        $a = $cd->getComponent();
        $this->assertIsA($a,'A_AbstractAutoRegisterTests');

        $cd = $register->findComponentDef('b');
        $this->assertEqual($cd,null);

        print "\n";
    }

    function testIsIgnore() {
        print __METHOD__ . "\n";

        $register = new Test_AbstractAutoRegisterTests();
        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames('Dao,Service$');
        $register->addIgnoreClassPatternInternal($cp);

        $this->assertTrue($register->isIgnore('HogeService'));
        $this->assertFalse($register->isIgnore('ServiceA'));

        print "\n";
    }
}
class A_AbstractAutoRegisterTests{}
    
class Test_AbstractAutoRegisterTests
    extends S2Container_AbstractAutoRegister{

    public function registerAll(){}
    
    public function isIgnore($name){
        return parent::isIgnore($name);    
    }

    public function hasComponentDef($name){
        return parent::hasComponentDef($name);    
    }

    public function findComponentDef($name){
        return parent::findComponentDef($name);    
    }
    
    public function addIgnoreClassPatternInternal(S2Container_ClassPattern $pattern){
        parent::addIgnoreClassPatternInternal($pattern);    
    }
}
?>
