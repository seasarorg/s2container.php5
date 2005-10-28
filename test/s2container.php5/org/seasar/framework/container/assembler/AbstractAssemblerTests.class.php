<?php
class AbstractAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetBeanDesc() {
       
        print __METHOD__ . "\n";       

        $asm = new TestAbstractAssembler (new S2Container_ComponentDefImpl('A'));       
        $this->assertIsA($asm,'TestAbstractAssembler');

        $desc = $asm->getBeanDescTest();
        $this->assertTrue($desc instanceof S2Container_BeanDesc);
        
        print "\n";
    }
    
    function testGetComponentClass(){
        print __METHOD__ . "\n";       

        $asm = new TestAbstractAssembler (new S2Container_ComponentDefImpl('A'));       

        $desc = $asm->getBeanDescTest(new B());
        $this->assertTrue($desc instanceof S2Container_BeanDesc);

        $c = $desc->getBeanClass();
        $this->assertTrue($c->getName() == 'A');
//        $this->assertTrue($c->getName() == 'B');
        
        print "\n";    	
    }
}

class TestAbstractAssembler extends S2Container_AbstractAssembler{
	
    public function TestAbstractAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }	
    
    public function getBeanDescTest($component=null) {
        return $this->getBeanDesc($component);
    }
}
?>