<?php
class ArgDefImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testGetValue() {
       
        print __METHOD__ . "\n";
       
        $arg = new ArgDefImpl();
        $this->assertNotNull($arg);

        $arg->setValue('test val');
        $this->assertEqual($arg->getValue(),'test val');
        
        $arg->setExpression('100');
        $this->assertEqual($arg->getValue(),'100');

        $arg = new ArgDefImpl();
        $this->assertNotNull($arg);

        $cd = new ComponentDefImpl('A','a');
        $arg->setChildComponentDef($cd);
        $this->assertIsA($arg->getValue(),'A');

        print "\n";
    } 

    function testMetaDef(){

        print __METHOD__ . "\n";

        $arg = new ArgDefImpl();
        $this->assertNotNull($arg);
 
        $md1 = new MetaDefImpl('a','A');
    	$arg->addMetaDef($md1);
        $md2 = new MetaDefImpl('b','B');
    	$arg->addMetaDef($md2);
        $md3 = new MetaDefImpl('c','C');
    	$arg->addMetaDef($md3);

        $this->assertEqual($arg->getMetaDefSize(),3);

        $md = $arg->getMetaDef('a');
        $this->assertReference($md,$md1);
    
        $md = $arg->getMetaDef(1);
        $this->assertReference($md,$md2);

        print "\n";
    }

    function testMetaDefs(){

        print __METHOD__ . "\n";

        $arg = new ArgDefImpl();
        $this->assertNotNull($arg);
 
        $md1 = new MetaDefImpl('a','A1');
    	$arg->addMetaDef($md1);
        $md2 = new MetaDefImpl('a','A2');
    	$arg->addMetaDef($md2);
        $md3 = new MetaDefImpl('a','A3');
    	$arg->addMetaDef($md3);

        $this->assertEqual($arg->getMetaDefSize(),3);

        $mds = $arg->getMetaDefs('a');
        $this->assertEqual(count($mds),3);
        $this->assertReference($mds[0],$md1);
    
        print "\n";
    }
}
?>