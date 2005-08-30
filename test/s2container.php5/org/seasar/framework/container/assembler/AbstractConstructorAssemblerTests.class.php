<?php
class AbstractConstructorAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testAssembleDefault() {
       
        print __METHOD__ . "\n";       

        $asm = new TestAbstractConstructorAssembler(new ComponentDefImpl('A'));       
        $this->assertIsA($asm,'TestAbstractConstructorAssembler');

        $a = $asm->assembleDefaultTest();
        $this->assertTrue($a instanceof A);
        
        print "\n";
    }
}

class TestAbstractConstructorAssembler extends AbstractConstructorAssembler{
	
    public function TestAbstractAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }	
    
    public function assembleDefaultTest() {
        return $this->assembleDefault();
    }

    public function assemble() {
    }
}
?>