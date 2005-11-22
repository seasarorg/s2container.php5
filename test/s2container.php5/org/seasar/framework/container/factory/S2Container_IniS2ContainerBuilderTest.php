<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
/**
 * @package org.seasar.framework.container.factory
 */
/**
 * @file S2Container_IniS2ContainerBuilderTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.factory
 * @class S2Container_IniS2ContainerBuilderTest
 */
class IniS2ContainerBuilderTests extends PHPUnit2_Framework_TestCase {

    /**
     * Construct Testcase
     */
    public function __construct() {
         parent::__construct();
    }

    /**
     * Setup Testcase
     */
    public function setUp() {
        parent::setUp();
    }

    /**
     * Clean up Testcase
     */
    public function tearDown() {
        parent::tearDown();
    }
            
    /**
     * testDicon
     * @return 
     */
    public function testDicon() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test1.ini');
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertType('A', $a);

        $b = $container->getComponent('test1.b');
        $this->assertType('A', $b);
    }
            
    /**
     * testArgValue
     * @return 
     */
    public function testArgValue() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test2.ini');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEquals($n->getVal1(),'test value.');
    }
            
    /**
     * testArgExp
     * @return 
     */
    public function testArgExp() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test3.ini');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEquals($n->getVal1(),2);

        $m = $container->getComponent('m');
        $this->assertEquals($m->getVal1(),9);

        $l = $container->getComponent('l');
        $this->assertEquals($l->getVal1(),"test desu");
    }
            
    /**
     * testArgComp
     * @return 
     */
    public function testArgComp() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test4.ini');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertType('D', $n->getVal1());

        $m = $container->getComponent('m');
        $this->assertType('D', $m->getVal1());
    }
            
    /**
     * testArgs
     * @return 
     */
    public function testArgs() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test5.ini');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('r');
        $this->assertEquals($r->getVal1(),'val 1');
        $this->assertEquals($r->getVal2(),'val 2');

        $r = $container->getComponent('a');
        $this->assertType('D', $r->getVal1());
        $this->assertEquals($r->getVal2(),'val 2');

        $r = $container->getComponent('b');
        $this->assertType('D', $r->getVal1());
        $this->assertType('D', $r->getVal2());
    }
            
    /**
     * testProperty
     * @return 
     */
    public function testProperty() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test6.ini');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('s');
        $this->assertEquals($r->getVal1(),'val 1.');
        $this->assertType('D', $r->getVal2());

        $t = $container->getComponent('t');
        $this->assertEquals($t->getVal1(),2);
    }
            
    /**
     * testInitMethod
     * @return 
     */
    public function testInitMethod() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test7.ini');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('r');
        $this->assertEquals($r->getVal1(),'val 1.');

        $compDef = $container->getComponentDef("r");
        $meDef = $compDef->getInitMethodDef(0);

        $argDef = $meDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertType('B', $m->getValue());
    }
            
    /**
     * testDestroyMethod
     * @return 
     */
    public function testDestroyMethod() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test8.ini');
        $this->assertNotNull($container);
        $container->init();
              
        $r = $container->getComponent('r');
        $container->destroy();

        $compDef = $container->getComponentDef("r");
        $meDef = $compDef->getDestroyMethodDef(0);
        $this->assertNotNull($meDef);

        $argDef = $meDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertType('B', $m->getValue());

        $this->assertEquals($argDef->getValue(),'end of century.');
    }
            
    /**
     * testAspect
     * @return 
     */
    public function testAspect() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test9.ini');
        $this->assertNotNull($container);
              
        $date = $container->getComponent('date');
        $this->assertEquals($date->getTime(),'12:00:30');

        $date = $container->getComponent('e');
        $this->assertEquals($date->ma(),'ma called.');
    }
            
    /**
     * testMeta
     * @return 
     */
    public function testMeta() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test10.ini');
        $this->assertNotNull($container);
              
        $m = $container->getMetaDef("m1");
        $this->assertNotNull($m);
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $container->getMetaDef("m2");
        $this->assertNotNull($m);
        $this->assertEquals($m->getValue(),2);

        $m = $container->getMetaDef("m3");
        $this->assertNotNull($m);
        $this->assertType('N', $m->getValue());

        $c = $container->getComponentDef("n");
        $m = $c->getMetaDef("m4");
        $this->assertNotNull($c);
        $this->assertNotNull($m);
        $this->assertEquals($m->getValue(),'m2-val2');
    }
            
    /**
     * testInclude
     * @return 
     */
    public function testInclude() {
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test11.ini');
        $this->assertNotNull($container);
        $a = $container->getComponent("a");
        $n = $container->getComponent("test2.n");
        $this->assertType('A', $a);
        $this->assertType('N', $n);
    }
            
    /**
     * testCalc
     * @return 
     */
    public function testCalc() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test12.ini');
        $a = $container->getComponent("addAction");
        $this->assertEquals($a->add(2,3),5);
    }
            
    /**
     * testCalc
     * @return 
     */
    public function testCalcAuto() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test13.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEquals($a->add(5,3),8);
    }
            
    /**
     * testPrototype
     * @return 
     */
    public function testPrototype() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test14.ini');
        $a = $container->getComponent("AddActionImpl");
        $b = $container->getComponent("AddActionImpl");
       
        if($a === $b){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
    }
            
    /**
     * testOuter
     * @return 
     */
    public function testOuter() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test15.ini');
        $s = new SubActionImpl();
        $container->injectDependency($s);
        $this->assertEquals($s->sub(3,2),1);
    }
            
    /**
     * testAop
     * @return 
     */
    public function testAop() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test16.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEquals($a->add(5,3),8);
        $s = $container->getComponent("SubActionImpl");
        $this->assertEquals($s->sub(5,3),2);
    }
            
    /**
     * testSingletonFactory
     * @return 
     */
    public function testSingletonFactory() {
       
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $this->assertNotNull($container);

        $a = $container->getComponent("a");
        $this->assertType('A', $a);
    }
            
    /**
     * testBinding
     * @return 
     */
    public function testBinding() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test17.ini');
        $o = $container->getComponent("O");
        $g = $container->getComponent("G");

        $s1 = $container->getComponent("s1");
        $oo = $s1->getRefO();
        $this->assertSame($oo,$o);
        $gg = $s1->getRefG();
        $this->assertSame($gg,$g);

        $s2 = $container->getComponent("s2");
        $oo = $s2->getRefO();
        $this->assertSame($oo,$o);
        $gg = $s2->getRefG();
        $this->assertNull($gg);


        $s3 = $container->getComponent("s3");
        $gg = $s3->getRefG();
        $this->assertSame($gg,$g);
        $this->assertEquals($s3->getVal1(),'val 1.');
       
        $s4 = $container->getComponent("s4");
        $oo = $s4->getRefO();
        $this->assertSame($oo,$o);
        $gg = $s4->getRefG();
        $this->assertNull($gg);
        $this->assertEquals($s4->getVal1(),'val 1.');
    }
            
    /**
     * testRequest
     * @return 
     */
    public function testRequest() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test19.ini');
        $l = $container->getComponent("l");
        $this->assertType('D', $l->getComp());

        $ll = $container->getComponent('l');
        $this->assertSame($l,$ll);

        $_REQUEST['l'] = "test string";
          
        $l = $container->getComponent('l');
        $this->assertType('L', $l);
    }
            
    /**
     * testSession
     * @return 
     */
    public function testSession() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test19.ini');
        $ll = $container->getComponent("ll");
        $this->assertType('D', $ll->getComp());

        $llll = $container->getComponent('ll');
        $this->assertSame($ll,$llll);

        $_SESSION['ll'] = "test string";
          
        $ll = $container->getComponent('ll');
        $this->assertType('L', $ll);
    }
            
    /**
     * testUuSet
     * @return 
     */
    public function testUuSet() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test20.ini');
        $m = $container->getComponent("m");
        $this->assertEquals($m->getName(),"test");
    }
            
    /**
     * testMockInterceptor
     * @return 
     */
    public function testMockInterceptor() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test21.ini');
        $a = $container->getComponent("d");
        $this->assertEquals($a->ma(),-1);
    }
            
    /**
     * testSameName
     * @return 
     */
    public function testSameName() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test22.ini');
        $this->assertNotNull($container);
       
        try{
            $a = $container->getComponent('A');
            $this->assertType('A', $a);
        }catch(Exception $e){
            $this->assertType('S2Container_TooManyRegistrationRuntimeException', $e);
        }
    }
            
    /**
     * testContainerInject
     * @return 
     */
    public function testContainerInject() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test23.ini');
        $this->assertNotNull($container);
       
        $v = $container->getComponent('V');
        $con = $v->getContainer();
        $this->assertSame($container,$con);
    }
            
    /**
     * testUuCallAopProxyFactory
     * @return 
     */
    public function testUuCallAopProxyFactory() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test24.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEquals($a->add(5,3),5);
    }
            
    /**
     * testInclude
     * @return 
     */
    public function testIncludeChild() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test25.ini');
        $a = $container->getComponent("AddActionImpl");
        $this->assertEquals($a->add(5,3),5);

        $b = $container->getComponent("test25-1.AddActionImpl");
        $this->assertEquals($b->add(5,3),10);

        if($a === $b){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }

        $c = $container->getComponent("test25-1-1.AddActionImpl");
        $this->assertEquals($c->add(5,3),11);

        $c = $container->getComponent("test25-2.AddActionImpl");
        $this->assertEquals($c->add(5,3),12);
    }
            
    /**
     * testPointcutEreg
     * @return 
     */
    public function testPointcutEreg() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test26.ini');
              
        $date = $container->getComponent('date');
        $this->assertEquals($date->getTime(),'12:00:30');

        $day = $container->getComponent('day');
        $this->assertEquals($day->getDay(),'25');

        $day = $container->getComponent('day2');
        $this->assertEquals($day->getDay(),'25');
        $this->assertEquals($day->getTime(),'12:00:30');
    }
            
    /**
     * testArgMeta
     * @return 
     */
    public function testArgMeta() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test27.ini');
              
        $compDef = $container->getComponentDef("n");
        $argDef = $compDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertType('B', $m->getValue());

        
        $this->assertEquals($argDef->getValue(),'test value.');
    }
            
    /**
     * testProperty
     * @return 
     */
    public function testPropertyMeta() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test28.ini');
       
        $r = $container->getComponent('r');
        $this->assertEquals($r->getVal1(),'val 1.');

        $compDef = $container->getComponentDef("r");
        $propDef = $compDef->getPropertyDef("val1");

        $m = $propDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $propDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $propDef->getMetaDef("m3");
        $this->assertType('B', $m->getValue());
    }
            
    /**
     * testCompExp
     * @return 
     */
    public function testCompExp() {
       
        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test29.ini');

        $b = $container->getComponent('B');
        $this->assertType('B', $b);

        $b = $container->getComponent('b1');
        $this->assertType('B', $b);

        $b = $container->getComponent('b2');
        $this->assertType('B', $b);
        
        $b = $container->getComponent('b3');
        $this->assertType('B', $b);

        $b = $container->getComponent('b4');
        $this->assertType('B', $b);

        try{
            $b = $container->getComponent('b5');
            $this->assertType('B', $b);
        }catch(Exception $e){
            $this->assertType('S2Container_ClassUnmatchRuntimeException', $e);
        }

        $b = $container->getComponent('b6');
        $this->assertType('B', $b);
    }
            
    /**
     * testInitMethod
     * @return 
     */
    public function testInitMethodExp() {

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test30.ini');
       
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),6);
        
        $container->init();
        $container->destroy();
    }
            
    /**
     * testChildComponent
     * @return 
     */
    public function testChildComponent() {

        $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test31.ini');
       
        $n = $container->getComponent('n');
        $this->assertType('N', $n);

        $d = $container->getComponent('d');
        $this->assertType('D', $d);

        $n2 = $container->getComponent('n2');
        $this->assertType('N', $n2);
        
        $d2 = $n2->getVal1();
        
        $this->assertType('D', $d2);
        $this->assertSame($d,$d2);
    }
            
    /**
     * testCircularIncludeRuntimeException
     * @return 
     */
    public function testCircularIncludeRuntimeException() {
        try{ 
            $container = S2ContainerFactory::create(TEST_DIR . '/s2container.php5/org/seasar/framework/container/factory/ini/test32.ini');
        }catch(Exception $e){
        	$this->assertType('S2Container_CircularIncludeRuntimeException', $e);
        }
    }
}
?>