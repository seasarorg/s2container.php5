<?php
class AbstractConstructorAssemblerTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testAssembleDefault() {
       
        print __METHOD__ . "\n";       

        $asm = new TestAbstractConstructorAssembler(new S2Container_ComponentDefImpl('A'));       
        $this->assertIsA($asm,'TestAbstractConstructorAssembler');

        $a = $asm->assembleDefaultTest();
        $this->assertTrue($a instanceof A);
        
        print "\n";
    }
}

class TestAbstractConstructorAssembler extends S2Container_AbstractConstructorAssembler{
	
    public function TestAbstractAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }	
    
    public function assembleDefaultTest() {
        return $this->assembleDefault();
    }

    public function assemble() {
    }
}
?>