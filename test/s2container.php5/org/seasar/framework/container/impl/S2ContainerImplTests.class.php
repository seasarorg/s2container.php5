<?php
class S2ContainerImplTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testInstatiation() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $this->assertNotNull($container);

        print "\n";
    }

    function testRegister1() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register(new B(),'b');
        $bdef = $container->getComponentDef('b');
        $b = $container->getComponent('b');
        $this->assertNotNull($container);

        print "\n";
    }

    function testRegister2() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('B','b');
          
        $bdef = $container->getComponentDef('b');
        $this->assertNotNull($container);

        print "\n";
    }

    function testRegister3() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D','d1');
        $container->register('D','d2');
          
        $d1 = $container->getComponent('d1');

        $this->assertIsA($d1,'D');

        print "\n";
    }

    function testRegister4() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('L','l');
          
        $l = $container->getComponent('l');

        $this->assertIsA($l,'L');

        print "\n";
    }

    function testTooManyRegister() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('D');
        $container->register('D');
        $ddef = $container->getComponentDef('D');
          
        try{
            $d = $container->getComponent('D');
        }catch(Exception $e){
            $this->assertIsA($e,'TooManyRegistrationRuntimeException');
        }

        print "\n";
    }

    function testCompNotFound() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
  
        try{
            $d = $container->getComponent('D');
        }catch(Exception $e){
            $this->assertIsA($e,'ComponentNotFoundRuntimeException');
            $this->assertEqual($e->getComponentKey(),'D');
        }

        print "\n";
    }
   
    function testCyclicRef() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('J');
        $container->register('K');
  
        try{
            $d = $container->getComponent('J');
        }catch(Exception $e){
            $this->assertIsA($e,'CyclicReferenceRuntimeException');
        }

        print "\n";
    }

    function testNamesapce() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->setNamespace('nemu');
        $container->register('D');
  
        $c = $container->getComponent('nemu');
        $this->assertReference($c,$container);

        print "\n";
    }

    function testChildConWithNamespace() {
       
        print __METHOD__ . "\n";
       
        $root = new S2ContainerImpl();
        $root->setNamespace('ro');
        $child = new S2ContainerImpl();
        $child->setNamespace('ch');
        $child->register('D');
  
        $root->includeChild($child);
       
        try{
            $d = $root->getComponent('D');
        }catch(Exception $e){
            $this->assertIsA($e,'ComponentNotFoundRuntimeException');
            $this->assertEqual($e->getComponentKey(),'D');
        }

        $ch = $root->getComponent('ch');
        $this->assertReference($ch,$child);
       
        $d = $root->getComponent('ch.D');
        $this->assertIsA($d,'D');
       
        print "\n";
    }

    function testChildCon() {
       
        print __METHOD__ . "\n";
       
        $root = new S2ContainerImpl();
        $child1 = new S2ContainerImpl();
        $child1->register('D');
        $child2 = new S2ContainerImpl();
  
        $root->includeChild($child1);
        $root->includeChild($child2);
       
        $d = $root->getComponent('D');
        $this->assertIsA($d,'D');
       
        print "\n";
    }

    function testSample() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('N','n');
          
        $cd = $container->getComponentDef('n');
        $arg = new ArgDefImpl();
        $arg->setValue("aaa");
        $cd->addArgDef($arg);

        $cd->setAutoBindingMode(
            ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new PropertyDefImpl('val2','bbb');
        $cd->addPropertyDef($pro);

        $n = $container->getComponent('n');
        $this->assertEqual($n->getVal1(),'aaa');
        $this->assertEqual($n->getVal2(),'bbb');

        print "\n";
    }
    
    function testGetComponentDef() {
       
        print __METHOD__ . "\n";
       
        $container = new S2ContainerImpl();
        $container->register('B','b');
        $def = $container->getComponentDef(new B());
        $this->assertIsA($def,'ComponentDefImpl');

        $container->register('B','bb');
        $def = $container->getComponentDef(0);
        $this->assertIsA($def,'ComponentDefImpl');
        $def = $container->getComponentDef(1);
        $this->assertIsA($def,'ComponentDefImpl');
          
        try{
            $def = $container->getComponentDef(2);
            $this->assertIsA($def,'ComponentDefImpl');
        }catch(Exception $e){
            $this->assertIsA($e,'ComponentNotFoundRuntimeException');
        }

        print "\n";
    }   

    function testGetComponentDefSize() {
       
        print __METHOD__ . "\n";

        $container = new S2ContainerImpl();
        $container->register('A','a');
        $container->register('b','b');

        $this->assertEqual($container->getComponentDefSize(),2);

        print "\n";
    } 
        
    function testDescendant() {
       
        print __METHOD__ . "\n";

        $dc = new S2ContainerImpl();
        $dc->setPath('d:/hoge.dicon');
           
        $container = new S2ContainerImpl();
        $container->registerDescendant($dc);

        $this->assertTrue($container->hasDescendant($dc->getPath()));
       
        $dc2 = $container->getDescendant($dc->getPath());
        $this->assertReference($dc,$dc2);

        print "\n";
    }     

    function testMetaDefSupport() {
       
        print __METHOD__ . "\n";

        $container = new S2ContainerImpl();
               
        $meta1 = new MetaDefImpl('a');
        $meta2 = new MetaDefImpl('b');
        $meta3 = new MetaDefImpl('c');
        
        $container->addMetaDef($meta1);
        $container->addMetaDef($meta2);
        $container->addMetaDef($meta3);
        
        $this->assertEqual($container->getMetaDefSize(),3);
        $meta = $container->getMetaDef(1);
        $this->assertReference($meta,$meta2);

        $meta = $container->getMetaDef('c');
        $this->assertReference($meta,$meta3);

        print "\n";
    } 

    function testNoChildIndex() {
       
        print __METHOD__ . "\n";

        $container = new S2ContainerImpl();

        try{
            $container->getChild(2);	
        }catch(Exception $e){   
        	$this->assertIsA($e,'ContainerNotRegisteredRuntimeException');
        	print $e->getMessage() . "\n";
        }            

        print "\n";
    } 

    function testFindComponents() {
       
        print __METHOD__ . "\n";

        $container = new S2ContainerImpl();
        $components = $container->findComponents('A');
        $this->assertEqual(count($components),0);

        $container->register(new A(),'a');

        $components = $container->findComponents('A');
        $this->assertEqual(count($components),1);
        $this->assertIsA($components[0],'A');

        $container->register('D','A');
        $components = $container->findComponents('A');
        $this->assertEqual(count($components),2);
        $this->assertIsA($components[0],'A');
        $this->assertIsA($components[1],'D');

        print "\n";
    } 
}
?>