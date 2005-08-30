<?php
class AbstractAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetBeanDesc() {
       
        print __METHOD__ . "\n";       

        $asm = new TestAbstractAssembler (new ComponentDefImpl('A'));       
        $this->assertIsA($asm,'TestAbstractAssembler');

        $desc = $asm->getBeanDescTest();
        $this->assertTrue($desc instanceof BeanDesc);
        
        print "\n";
    }
    
    function testGetComponentClass(){
        print __METHOD__ . "\n";       

        $asm = new TestAbstractAssembler (new ComponentDefImpl('A'));       

        $desc = $asm->getBeanDescTest(new B());
        $this->assertTrue($desc instanceof BeanDesc);

        $c = $desc->getBeanClass();
        $this->assertTrue($c->getName() == 'A');
//        $this->assertTrue($c->getName() == 'B');
        
        print "\n";    	
    }
}

class TestAbstractAssembler extends AbstractAssembler{
	
    public function TestAbstractAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }	
    
    public function getBeanDescTest($component=null) {
        return $this->getBeanDesc($component);
    }
}
?>