<?php
class IniS2ContainerBuilderTests extends UnitTestCase {
    function __construct() {
        $this->UnitTestCase();
    }

    function testDicon() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test1.ini');
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertIsA($a,'A');

        $b = $container->getComponent('test1.b');
        $this->assertIsA($b,'A');
       
        print "\n";
    }

    function testArgValue() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test2.ini');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEqual($n->getVal1(),'test value.');

        print "\n";
    }

    function testArgExp() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test3.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test4.ini');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertIsA($n->getVal1(),'D');

        $m = $container->getComponent('m');
        $this->assertIsA($m->getVal1(),'D');

        print "\n";
    }

    function testArgs() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test5.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test6.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test7.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test8.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test9.ini');
        $this->assertNotNull($container);
              
        $date = $container->getComponent('date');
        $this->assertEqual($date->getTime(),'12:00:30');

        $date = $container->getComponent('e');
        $this->assertEqual($date->ma(),'ma called.');

        print "\n";
    }

    function testMeta() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test10.ini');
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
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test11.ini');
        $this->assertNotNull($container);
        $a = $container->getComponent("a");
        $n = $container->getComponent("test2.n");
        $this->assertIsA($a,'A');
        $this->assertIsA($n,'N');
        print "\n";
    }

    function testCalc() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test12.ini');
        $a = $container->getComponent("addAction");
        $this->assertEqual($a->add(2,3),5);

        print "\n";
    }

    function testCalcAuto() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test13.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),8);

        print "\n";
    }

    function testPrototype() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test14.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test15.ini');
        $s = new SubActionImpl();
        $container->injectDependency($s);
        $this->assertEqual($s->sub(3,2),1);

        print "\n";
    }
    
    function testAop() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test16.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),8);
        $s = $container->getComponent("SubActionImpl");
        $this->assertEqual($s->sub(5,3),2);

        print "\n";
    }


    function testSingletonFactory() {
       
        print __METHOD__ . "\n";
       
        $container = SingletonS2ContainerFactory::getContainer();
        $this->assertNotNull($container);

        $a = $container->getComponent("a");
        $this->assertIsA($a,'A');

        print "\n";
    }

    function testBinding() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test17.ini');
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test19.ini');
        $l = $container->getComponent("l");
        $this->assertIsA($l->getComp(),'D');

        $ll = $container->getComponent('l');
        $this->assertReference($l,$ll);

        $_REQUEST['l'] = "test string";
          
        $l = $container->getComponent('l');
        $this->assertIsA($l,'L');

        print "\n";
    }

    function testSession() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test19.ini');
        $ll = $container->getComponent("ll");
        $this->assertIsA($ll->getComp(),'D');

        $llll = $container->getComponent('ll');
        $this->assertReference($ll,$llll);

        $_SESSION['ll'] = "test string";
          
        $ll = $container->getComponent('ll');
        $this->assertIsA($ll,'L');

        print "\n";
    }


    function testUuSet() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test20.ini');
        $m = $container->getComponent("m");
        $this->assertEqual($m->getName(),"test");

        print "\n";
    }

    function testMockInterceptor() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test21.ini');
        $a = $container->getComponent("d");
        $this->assertEqual($a->ma(),-1);

        print "\n";
    }

    function testSameName() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test22.ini');
        $this->assertNotNull($container);
       
        try{
            $a = $container->getComponent('A');
            $this->assertIsA($a,'A');
        }catch(Exception $e){
            $this->assertIsA($e,'TooManyRegistrationRuntimeException');
        }

        print "\n";
    }

    function testContainerInject() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test23.ini');
        $this->assertNotNull($container);
       
        $v = $container->getComponent('V');
        $con = $v->getContainer();
        $this->assertReference($container,$con);

        print "\n";
    }
    
    function testUuCallAopProxyFactory() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test24.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEqual($a->add(5,3),5);

        print "\n";
    }

    function testIncludeChild() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test25.ini');
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
    
    function testPointcutEreg() {
       
        print __METHOD__ . "\n";
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test26.ini');
              
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test27.ini');
              
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test28.ini');
       
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
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test29.ini');

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
            $this->assertIsA($e,'ClassUnmatchRuntimeException');
            print $e->getMessage() . "\n";
        }

        $b = $container->getComponent('b6');
        $this->assertIsA($b,'B');

        print "\n";
    }

    function testInitMethodExp() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test30.ini');
       
        $i = $container->getComponent('i');
        $this->assertEqual($i->getResult(),6);
        
        $container->init();
        $container->destroy();
    
        print "\n";
    }

    function testChildComponent() {
       
        print __METHOD__ . "\n";

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test31.ini');
       
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
}
?>