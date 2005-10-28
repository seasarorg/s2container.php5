<?php
class XmlS2ContainerBuilderTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testDicon() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test1.dicon');
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A');

        $b = $container->getComponent('test1.b');
        $this->assertIsA($b,'A');
       
        print "\n";
    }
    
    function testComp() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test1.xml');
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A');

        $b = $container->getComponent('test1.b');
        $this->assertIsA($b,'A');
       
        print "\n";
    }

    function testArgValue() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test2.xml');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEqual($n->getVal1(),'test value.');

        print "\n";
    }

    function testArgExp() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test3.xml');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEqual($n->getVal1(),2);

        $m = $container->getComponent('m');
        $this->assertEqual($m->getVal1(),9);

        $l = $container->getComponent('l');
        $this->assertEqual($l->getVal1(),"test desu");

        print "\n";
    }

    function testArgComp() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test4.xml');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertIsA($n->getVal1(),'D');

        $m = $container->getComponent('m');
        $this->assertIsA($m->getVal1(),'D');

        $o = $container->getComponent('o');
        $this->assertIsA($o->getVal1(),'D');

        print "\n";
    }
    
    function testArgs() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test5.xml');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('r');
        $this->assertEqual($r->getVal1(),'val 1');
        $this->assertEqual($r->getVal2(),'val 2');

        $r = $container->getComponent('a');
        $this->assertIsA($r->getVal1(),'D');
        $this->assertEqual($r->getVal2(),'val 2');

        $r = $container->getComponent('b');
        $this->assertIsA($r->getVal1(),'D');
        $this->assertIsA($r->getVal2(),'D');

        print "\n";
    }

    function testProperty() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test6.xml');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('s');
        $this->assertEqual($r->getVal1(),'val 1.');
        $this->assertIsA($r->getVal2(),'D');

        $t = $container->getComponent('t');
        $this->assertEqual($t->getVal1(),2);

        print "\n";
    }

    function testInitMethod() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test7.xml');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('r');
        $this->assertEqual($r->getVal1(),'val 1.');

        $compDef = $container->getComponentDef("r");
        $meDef = $compDef->getInitMethodDef(0);

        $argDef = $meDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEqual($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEqual($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertIsA($m->getValue(),'B');

        print "\n";
    }

    function testDestroyMethod() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test8.xml');
        $this->assertNotNull($container);
        $container->init();
              
        $r = $container->getComponent('r');
        $container->destroy();

        $compDef = $container->getComponentDef("r");
        $meDef = $compDef->getDestroyMethodDef(0);

        $argDef = $meDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEqual($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEqual($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertIsA($m->getValue(),'B');

        $this->assertEqual($argDef->getValue(),'end of century.');

        print "\n";
    }

    function testAspect() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test9.xml');
        $this->assertNotNull($container);
              
        $date = $container->getComponent('date');
        $this->assertEqual($date->getTime(),'12:00:30');

        $date = $container->getComponent('e');
        $this->assertEqual($date->ma(),'ma called.');

        print "\n";
    }

    function testMeta() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test10.xml');
        $this->assertNotNull($container);
              
        $m = $container->getMetaDef("m1");
        $this->assertEqual($m->getValue(),'m1-val1');

        $m = $container->getMetaDef("m2");
        $this->assertEqual($m->getValue(),2);

        $m = $container->getMetaDef("m3");
        $this->assertIsA($m->getValue(),'N');

        $c = $container->getComponentDef("n");
        $m = $c->getMetaDef("m4");
        $this->assertEqual($m->getValue(),'m2-val2');

        print "\n";
    }

    function testInclude() {
       
        print __METHOD__ . "\n";
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test11.xml');
        $this->assertNotNull($container);
        $a = $container->getComponent("a");
        $n = $container->getComponent("test2.n");
        $this->assertIsA($a,'A');
        $this->assertIsA($n,'N');
        print "\n";
    }
    
    function testCalc() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test12.xml');
        $a = $container->getComponent("addAction");
        $this->assertEqual($a->add(2,3),5);

        print "\n";
    }

    function testCalcAuto() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test13.xml');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),8);

        print "\n";
    }

    function testPrototype() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test14.xml');
        $a = $container->getComponent("AddActionImpl");
        $b = $container->getComponent("AddActionImpl");
       
        if($a === $b){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
        print "\n";
    }

    function testOuter() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test15.xml');
        $s = new SubActionImpl();
        $container->injectDependency($s);
        $this->assertEqual($s->sub(3,2),1);

        print "\n";
    }
    
    function testAop() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test16.xml');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),8);
        $s = $container->getComponent("SubActionImpl");
        $this->assertEqual($s->sub(5,3),2);

        print "\n";
    }

    function testSingletonFactory() {
       
        print __METHOD__ . "\n";
       
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $this->assertNotNull($container);

        $a = $container->getComponent("a");
        $this->assertIsA($a,'A');

        print "\n";
    }

    function testBinding() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test17.dicon');
        $o = $container->getComponent("O");
        $g = $container->getComponent("G");

        $s1 = $container->getComponent("s1");
        $oo = $s1->getRefO();
        $this->assertReference($oo,$o);
        $gg = $s1->getRefG();
        $this->assertReference($gg,$g);

        $s2 = $container->getComponent("s2");
        $oo = $s2->getRefO();
        $this->assertReference($oo,$o);
        $gg = $s2->getRefG();
        $this->assertNull($gg);


        $s3 = $container->getComponent("s3");
        $gg = $s3->getRefG();
        $this->assertReference($gg,$g);
        $this->assertEqual($s3->getVal1(),'val 1.');
       
        $s4 = $container->getComponent("s4");
        $oo = $s4->getRefO();
        $this->assertReference($oo,$o);
        $gg = $s4->getRefG();
        $this->assertNull($gg);
        $this->assertEqual($s4->getVal1(),'val 1.');

        print "\n";
    }

    function testRequest() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test19.dicon');
        $l = $container->getComponent("l");
        $this->assertIsA($l->getComp(),'D');

        $ll = $container->getComponent('l');
        $this->assertReference($l,$ll);

        $_REQUEST['l'] = "test string";
          
        $l = $container->getComponent('l');
        $this->assertIsA($l,'L');
    }

    function testSession() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test19.dicon');
        $ll = $container->getComponent("ll");
        $this->assertIsA($ll->getComp(),'D');

        $llll = $container->getComponent('ll');
        $this->assertReference($ll,$llll);

        $_SESSION['ll'] = "test string";
          
        $ll = $container->getComponent('ll');
        $this->assertIsA($ll,'L');
    }

    function testUuSet() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test20.dicon');
        $m = $container->getComponent("m");
        $this->assertEqual($m->getName(),"test");

    }

    function testMockInterceptor() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test21.dicon');
        $a = $container->getComponent("d");
        $this->assertEqual($a->ma(),-1);

        print "\n";
    }
 
    function testSameName() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test22.dicon');
        $this->assertNotNull($container);
       
        try{
            $a = $container->getComponent('A');
            $this->assertIsA($a,'A');
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_TooManyRegistrationRuntimeException');
        }

        print "\n";
    }

    function testContainerInject() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test23.dicon');
        $this->assertNotNull($container);
       
        $v = $container->getComponent('V');
        $con = $v->getContainer();
        $this->assertReference($container,$con);

        print "\n";
    }
    
    function testUuCallAopProxyFactory() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test24.dicon');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),5);

        print "\n";
    }

    function testIncludeChild() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test25.dicon');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),5);

        $b = $container->getComponent("test25-1.AddActionImpl");
        $this->assertEqual($b->add(5,3),10);

        if($a === $b){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }

        $c = $container->getComponent("test25-1-1.AddActionImpl");
        $this->assertEqual($c->add(5,3),11);

        $c = $container->getComponent("test25-2.AddActionImpl");
        $this->assertEqual($c->add(5,3),12);

        print "\n";
    }
    
    function testPintcutEreg() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test26.dicon');
              
        $date = $container->getComponent('date');
        $this->assertEqual($date->getTime(),'12:00:30');

        $day = $container->getComponent('day');
        $this->assertEqual($day->getDay(),'25');

        $day = $container->getComponent('day2');
        $this->assertEqual($day->getDay(),'25');
        $this->assertEqual($day->getTime(),'12:00:30');

        print "\n";
    }
    
    function testArgMeta() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test27.dicon');
              
        $compDef = $container->getComponentDef("n");
        $argDef = $compDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEqual($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEqual($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertIsA($m->getValue(),'B');

        
        $this->assertEqual($argDef->getValue(),'test value.');

        print "\n";
    }

    function testPropertyMeta() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test28.dicon');
       
        $r = $container->getComponent('r');
        $this->assertEqual($r->getVal1(),'val 1.');

        $compDef = $container->getComponentDef("r");
        $propDef = $compDef->getPropertyDef("val1");

        $m = $propDef->getMetaDef("m1");
        $this->assertEqual($m->getValue(),'m1-val1');

        $m = $propDef->getMetaDef("m2");
        $this->assertEqual($m->getValue(),2);

        $m = $propDef->getMetaDef("m3");
        $this->assertIsA($m->getValue(),'B');

        print "\n";
    }

    function testCompExp() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test29.dicon');
       
        $b = $container->getComponent('B');
        $this->assertIsA($b,'B');

        $b = $container->getComponent('b1');
        $this->assertIsA($b,'B');

        $b = $container->getComponent('b2');
        $this->assertIsA($b,'B');
        
        $b = $container->getComponent('b3');
        $this->assertIsA($b,'B');
        
        $b = $container->getComponent('b4');
        $this->assertIsA($b,'B');

        try{
            $b = $container->getComponent('b5');
            $this->assertIsA($b,'B');
        }catch(Exception $e){
            $this->assertIsA($e,'S2Container_ClassUnmatchRuntimeException');
            print $e->getMessage() . "\n";
        }

        $b = $container->getComponent('b6');
        $this->assertIsA($b,'B');

        print "\n";
    }

    function testInitMethodExp() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test30.dicon');
       
        $i = $container->getComponent('i');
        $this->assertEqual($i->getResult(),6);
        
        $container->init();
        $container->destroy();
    
        print "\n";
    }

    function testChildComponent() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test31.dicon');
       
        $n = $container->getComponent('n');
        $this->assertIsA($n,'N');

        $d = $container->getComponent('d');
        $this->assertIsA($d,'D');

        $n2 = $container->getComponent('n2');
        $this->assertIsA($n2,'N');
        
        $d2 = $n2->getVal1();
        
        $this->assertIsA($d2,'D');
        $this->assertReference($d,$d2);
        
        print "\n";
    }

    function testSingletonS2ContainerDestroy() {
       
        print __METHOD__ . "\n";

        if(S2Container_SingletonS2ContainerFactory::hasContainer()){
        	print "S2Container_SingletonS2ContainerFactory destroy.\n";
        	S2Container_SingletonS2ContainerFactory::destroy();
        }
        $container = S2Container_SingletonS2ContainerFactory::getContainer(
                       TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test4.xml');
       
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertIsA($n->getVal1(),'D');

        $m = $container->getComponent('m');
        $this->assertIsA($m->getVal1(),'D');

        $o = $container->getComponent('o');
        $this->assertIsA($o->getVal1(),'D');
        if(S2Container_SingletonS2ContainerFactory::hasContainer()){
        	print "S2Container_SingletonS2ContainerFactory destroy.\n";
        	S2Container_SingletonS2ContainerFactory::destroy();
        }
        
        print "\n";
    }

    function testCircularIncludeRuntimeException() {
       
        print __METHOD__ . "\n";
        try{ 
            $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/xml/test32.dicon');
        }catch(Exception $e){
        	$this->assertIsA($e,'S2Container_CircularIncludeRuntimeException');
        	print $e->getMessage() . "\n";
        }

        print "\n";
    }
}
?>