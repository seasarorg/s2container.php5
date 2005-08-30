<?php
class ComponentDefImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testArgDefSupport() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
               
        $arg1 = new ArgDefImpl();
        $arg2 = new ArgDefImpl();
        $arg3 = new ArgDefImpl();
        
        $cd->addArgDef($arg1);
        $cd->addArgDef($arg2);
        $cd->addArgDef($arg3);
        
        $this->assertEqual($cd->getArgDefSize(),3);
        $arg = $cd->getArgDef(1);
        $this->assertReference($arg,$arg2);

        print "\n";
    } 

    function testPropertyDefSupport() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
               
        $prop1 = new PropertyDefImpl('a');
        $prop2 = new PropertyDefImpl('b');
        $prop3 = new PropertyDefImpl('c');
        
        $cd->addPropertyDef($prop1);
        $cd->addPropertyDef($prop2);
        $cd->addPropertyDef($prop3);
        
        $this->assertEqual($cd->getPropertyDefSize(),3);

        $prop = $cd->getPropertyDef(1);
        $this->assertReference($prop,$prop2);

        $prop = $cd->getPropertyDef('a');
        $this->assertReference($prop,$prop1);

        $this->assertTrue($cd->hasPropertyDef('c'));

        print "\n";
    } 

    function testInitMethodDefSupport() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
               
        $im1 = new InitMethodDefImpl('a');
        $im2 = new InitMethodDefImpl('b');
        $im3 = new InitMethodDefImpl('c');
        
        $cd->addInitMethodDef($im1);
        $cd->addInitMethodDef($im2);
        $cd->addInitMethodDef($im3);
        
        $this->assertEqual($cd->getInitMethodDefSize(),3);
        $im = $cd->getInitMethodDef(1);
        $this->assertReference($im,$im2);

        print "\n";
    } 

    function testDestroyMethodDefSupport() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
               
        $dm1 = new DestroyMethodDefImpl('a');
        $dm2 = new DestroyMethodDefImpl('b');
        $dm3 = new DestroyMethodDefImpl('c');
        
        $cd->addDestroyMethodDef($dm1);
        $cd->addDestroyMethodDef($dm2);
        $cd->addDestroyMethodDef($dm3);
        
        $this->assertEqual($cd->getDestroyMethodDefSize(),3);
        $dm = $cd->getDestroyMethodDef(1);
        $this->assertReference($dm,$dm2);

        print "\n";
    } 

    function testAspectDefSupport() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
               
        $aspect1 = new AspectDefImpl('a');
        $aspect2 = new AspectDefImpl('b');
        $aspect3 = new AspectDefImpl('c');
        
        $cd->addAspectDef($aspect1);
        $cd->addAspectDef($aspect2);
        $cd->addAspectDef($aspect3);
        
        $this->assertEqual($cd->getAspectDefSize(),3);
        $aspect = $cd->getAspectDef(1);
        $this->assertReference($aspect,$aspect2);

        print "\n";
    } 

    function testMetaDefSupport() {
       
        print __METHOD__ . "\n";

        $cd = new ComponentDefImpl('A','a');
               
        $meta1 = new MetaDefImpl('a');
        $meta2 = new MetaDefImpl('b');
        $meta3 = new MetaDefImpl('c');
        
        $cd->addMetaDef($meta1);
        $cd->addMetaDef($meta2);
        $cd->addMetaDef($meta3);
        
        $this->assertEqual($cd->getMetaDefSize(),3);
        $meta = $cd->getMetaDef(1);
        $this->assertReference($meta,$meta2);

        $meta = $cd->getMetaDef('c');
        $this->assertReference($meta,$meta3);

        print "\n";
    } 
}
?>