<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id:$
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
class S2Container_XmlS2ContainerBuilderTest
    extends PHPUnit2_Framework_TestCase {

    private $diconDir;
    public function __construct($name) {
        parent::__construct($name);
        $this->diconDir = dirname(__FILE__) . '/dicon/' . __CLASS__;
        if (!defined('DICON_DIR_S2Container_XmlS2ContainerBuilderTest')){
            define('DICON_DIR_S2Container_XmlS2ContainerBuilderTest',
                   $this->diconDir);
        }
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testDicon() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testDicon.dicon');
        $this->assertType('S2Container',$container);
       
        $a = $container->getComponent('a');
        $this->assertType('A_S2Container_XmlS2ContainerBuilder',$a);

        $b = $container->getComponent('testDicon.b');
        $this->assertType('A_S2Container_XmlS2ContainerBuilder',$b);
    }

    function testComp() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testComp.xml');
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertType('A_S2Container_XmlS2ContainerBuilder',$a);

        $b = $container->getComponent('testComp.b');
        $this->assertType('A_S2Container_XmlS2ContainerBuilder',$b);
    }

    function testArgValue() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testArgValue.xml');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEquals($n->getVal1(),'test value.');
    }

    function testArgExp() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testArgExp.xml');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertEquals($n->getVal1(),2);

        $m = $container->getComponent('m');
        $this->assertEquals($m->getVal1(),9);

        $l = $container->getComponent('l');
        $this->assertEquals($l->getVal1(),"test desu");
    }

    function testArgComp() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testArgComp.xml');
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$n->getVal1());

        $m = $container->getComponent('m');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$m->getVal1());

        $o = $container->getComponent('o');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$o->getVal1());
    }
    
    function testArgs() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testArgs.xml');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('r');
        $this->assertEquals($r->getVal1(),'val 1');
        $this->assertEquals($r->getVal2(),'val 2');

        $r = $container->getComponent('a');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$r->getVal1());
        $this->assertEquals($r->getVal2(),'val 2');

        $r = $container->getComponent('b');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$r->getVal1());
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$r->getVal2());
    }

    function testProperty() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testProperty.xml');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('s');
        $this->assertEquals($r->getVal1(),'val 1.');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$r->getVal2());

        $t = $container->getComponent('t');
        $this->assertEquals(2,$t->getVal1());
    }

    function testInitMethod() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testInitMethod.xml');
        $this->assertNotNull($container);
       
        $r = $container->getComponent('r');
        $this->assertEquals('val 1.',$r->getVal1());

        $compDef = $container->getComponentDef("r");
        $meDef = $compDef->getInitMethodDef(0);

        $argDef = $meDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$m->getValue());
    }

    function testDestroyMethod() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testDestroyMethod.xml');
        $this->assertNotNull($container);
        $container->init();
              
        $r = $container->getComponent('r');
        $container->destroy();

        $compDef = $container->getComponentDef("r");
        $meDef = $compDef->getDestroyMethodDef(0);

        $argDef = $meDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$m->getValue());

        $this->assertEquals($argDef->getValue(),'end of century.');
    }

    function testAspect() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testAspect.xml');
        $this->assertNotNull($container);
              
        $date = $container->getComponent('date');
        $this->assertEquals($date->getTime(),'12:00:30');

        $date = $container->getComponent('e');
        $this->assertEquals($date->ma(),'ma called.');
    }

    function testMeta() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testMeta.xml');
        $this->assertNotNull($container);
              
        $m = $container->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $container->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $container->getMetaDef("m3");
        $this->assertType('N_S2Container_XmlS2ContainerBuilder',$m->getValue());

        $c = $container->getComponentDef("n");
        $m = $c->getMetaDef("m4");
        $this->assertEquals($m->getValue(),'m2-val2');
    }

    function testInclude() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testIncludeA.xml');
        $this->assertNotNull($container);
        $a = $container->getComponent("a");
        $n = $container->getComponent("testIncludeB.n");
        $this->assertType('A_S2Container_XmlS2ContainerBuilder',$a);
        $this->assertType('N_S2Container_XmlS2ContainerBuilder',$n);
    }
    
    function testCalc() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testCalc.xml');
        $a = $container->getComponent("addAction");
        $this->assertEquals($a->add(2,3),5);
    }

    function testCalcAuto() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testCalcAuto.xml');
        $a = $container->getComponent("AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($a->add(5,3),8);
    }

    function testPrototype() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testPrototype.xml');
        $a = $container->getComponent("AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $b = $container->getComponent("AddActionImpl_S2Container_XmlS2ContainerBuilder");
       
        if($a === $b){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }
    }

    function testOuter() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testOuter.xml');
        $s = new SubActionImpl_S2Container_XmlS2ContainerBuilder();
        $container->injectDependency($s);
        $this->assertEquals($s->sub(3,2),1);
    }
    
    function testAop() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testAop.xml');
        $a = $container->getComponent("AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($a->add(5,3),8);
        $s = $container->getComponent("SubActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($s->sub(5,3),2);
    }

    function testBinding() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testBinding.dicon');
        $o = $container->getComponent("O_S2Container_XmlS2ContainerBuilder");
        $g = $container->getComponent("G_S2Container_XmlS2ContainerBuilder");

        $s1 = $container->getComponent("s1");
        $oo = $s1->getRefO();
        $this->assertTrue($oo === $o);
        $gg = $s1->getRefG();
        $this->assertTrue($gg === $g);

        $s2 = $container->getComponent("s2");
        $oo = $s2->getRefO();
        $this->assertTrue($oo === $o);
        $gg = $s2->getRefG();
        $this->assertNull($gg);


        $s3 = $container->getComponent("s3");
        $gg = $s3->getRefG();
        $this->assertTrue($gg === $g);
        $this->assertEquals($s3->getVal1(),'val 1.');
       
        $s4 = $container->getComponent("s4");
        $oo = $s4->getRefO();
        $this->assertTrue($oo === $o);
        $gg = $s4->getRefG();
        $this->assertNull($gg);
        $this->assertEquals($s4->getVal1(),'val 1.');
    }

    function testRequest() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testRequest.dicon');
        $l = $container->getComponent("l");
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$l->getComp());

        $ll = $container->getComponent('l');
        $this->assertTrue($l === $ll);

        $_REQUEST['l'] = "test string";
          
        $l = $container->getComponent('l');
        $this->assertType('L_S2Container_XmlS2ContainerBuilder',$l);
    }

    function testSession() {
        $_SESSION = array();
        session_id('test');
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testSession.dicon');
        $ll = $container->getComponent("ll");
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$ll->getComp());

        $llll = $container->getComponent('ll');
        $this->assertTrue($ll === $llll);

        $_SESSION['ll'] = "test string";
          
        $ll = $container->getComponent('ll');
        $this->assertType('L_S2Container_XmlS2ContainerBuilder',$ll);
        $_SESSION = null;
        session_id('');
    }

    function testUuSet() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testUuSet.dicon');
        $m = $container->getComponent("m");
        $this->assertEquals($m->getName(),"test");
    }

    function testMockInterceptor() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testMockInterceptor.dicon');
        $a = $container->getComponent("d");
        $this->assertEquals($a->ma(),'-1');
    }
 
    function testSameName() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testSameName.dicon');
        $this->assertNotNull($container);
       
        try{
            $a = $container->getComponent('A_S2Container_XmlS2ContainerBuilder');
            $this->assertTrue($a instanceof A_S2Container_XmlS2ContainerBuilder);
        }catch(Exception $e){
            $this->fail($e->__toString());
        }
    }

    function testContainerInject() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testContainerInject.dicon');
        $this->assertNotNull($container);
       
        $v = $container->getComponent('V_S2Container_XmlS2ContainerBuilder');
        $con = $v->getContainer();
        $this->assertTrue($container === $con);
    }
    
    function testUuCallAopProxyFactory() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testUuCallAopProxyFactory.dicon');
        $a = $container->getComponent("AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($a->add(5,3),'5');
    }

    function testIncludeChild() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testIncludeChild.dicon');
        $a = $container->getComponent("AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($a->add(5,3),'5');

        $b = $container->getComponent("testIncludeChildB.AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($b->add(5,3),'10');

        if($a === $b){
            $this->assertTrue(false);
        }else{
            $this->assertTrue(true);
        }

        $c = $container->getComponent("testIncludeChildD.AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($c->add(5,3),'11');

        $c = $container->getComponent("testIncludeChildC.AddActionImpl_S2Container_XmlS2ContainerBuilder");
        $this->assertEquals($c->add(5,3),'12');
    }
    
    function testPointcutEreg() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testPintcutEreg.dicon');
              
        $date = $container->getComponent('date');
        $this->assertEquals($date->getTime(),'12:00:30');

        $day = $container->getComponent('day');
        $this->assertEquals($day->getDay(),'25');

        $day = $container->getComponent('day2');
        $this->assertEquals($day->getDay(),'25');
        $this->assertEquals($day->getTime(),'12:00:30');
    }
    
    function testArgMeta() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testArgMeta.dicon');              
        $compDef = $container->getComponentDef("n");
        $argDef = $compDef->getArgDef(0);
        $this->assertNotNull($argDef);

        $m = $argDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $argDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $argDef->getMetaDef("m3");
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$m->getValue());
        
        $this->assertEquals($argDef->getValue(),'test value.');
    }

    function testPropertyMeta() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testPropertyMeta.dicon');              
       
        $r = $container->getComponent('r');
        $this->assertEquals($r->getVal1(),'val 1.');

        $compDef = $container->getComponentDef("r");
        $propDef = $compDef->getPropertyDef("val1");

        $m = $propDef->getMetaDef("m1");
        $this->assertEquals($m->getValue(),'m1-val1');

        $m = $propDef->getMetaDef("m2");
        $this->assertEquals($m->getValue(),2);

        $m = $propDef->getMetaDef("m3");
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$m->getValue());
    }

    function testCompExp() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testCompExp.dicon');              
       
        $b = $container->getComponent('B_S2Container_XmlS2ContainerBuilder');
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);

        $b = $container->getComponent('b1');
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);

        $b = $container->getComponent('b2');
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);
        
        $b = $container->getComponent('b3');
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);
        
        $b = $container->getComponent('b4');
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);

        try{
            $b = $container->getComponent('b5');
            $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);
        }catch(Exception $e){
            $this->assertType('S2Container_ClassUnmatchRuntimeException',$e);
            print $e->getMessage() . "\n";
        }

        $b = $container->getComponent('b6');
        $this->assertType('B_S2Container_XmlS2ContainerBuilder',$b);
    }

    function testInitMethodExp() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testInitMethodExp.dicon');              
       
        $i = $container->getComponent('i');
        $this->assertEquals($i->getResult(),6);
        
        $container->init();
        $container->destroy();
    }

    function testChildComponent() {
        $container = S2ContainerFactory::create(
                                $this->diconDir . '/testChildComponent.dicon');              
       
        $n = $container->getComponent('n');
        $this->assertType('N_S2Container_XmlS2ContainerBuilder',$n);

        $d = $container->getComponent('d');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$d);

        $n2 = $container->getComponent('n2');
        $this->assertType('N_S2Container_XmlS2ContainerBuilder',$n2);
        
        $d2 = $n2->getVal1();
        
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$d2);
        $this->assertTrue($d === $d2);
    }

    function testSingletonS2ContainerDestroy() {
        if(S2Container_SingletonS2ContainerFactory::hasContainer()){
            print "S2Container_SingletonS2ContainerFactory destroy.\n";
            S2Container_SingletonS2ContainerFactory::destroy();
        }
        $container = S2Container_SingletonS2ContainerFactory::getContainer(
                       $this->diconDir . '/testSingletonS2ContainerDestroy.xml');
       
        $this->assertNotNull($container);
       
        $n = $container->getComponent('n');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$n->getVal1());

        $m = $container->getComponent('m');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$m->getVal1());

        $o = $container->getComponent('o');
        $this->assertType('D_S2Container_XmlS2ContainerBuilder',$o->getVal1());
        if(S2Container_SingletonS2ContainerFactory::hasContainer()){
            print "S2Container_SingletonS2ContainerFactory destroy.\n";
            S2Container_SingletonS2ContainerFactory::destroy();
        }
    }

    function testCircularIncludeRuntimeException() {
        try{ 
            $container = S2ContainerFactory::create(
                             $this->diconDir . '/testCircularIncludeRuntimeExceptionA.dicon');
        }catch(Exception $e){
            $this->assertType('S2Container_CircularIncludeRuntimeException',$e);
            print $e->getMessage() . "\n";
        }
    }

    function testReflectionNotFoundException() {
        try{ 
            $container = S2ContainerFactory::create(
                             $this->diconDir . '/testReflectionNotFoundException.dicon');
            $container->getComponent('service');
            $this->fail();
        }catch(Exception $e){
            print $e->getMessage() . "\n";
        }
    }
    /*
    function testIntertype(){
        // property intertype
        $container = S2ContainerFactory::create($this->diconDir . '/testIntertype.xml');
        $this->assertNotNull($container);
        
        $a = $container->getComponent("a");
        $this->assertNotNull($a);
        
        // setter has
        $a->setAaa("aaa");
        $a->setBbb("bbb");
        $a->setCcc("ccc");
        
        // getter has eq value
        $this->assertEquals($a->getAaa(), "aaa");
        $this->assertEquals($a->getBbb(), "bbb");
        $this->assertEquals($a->getCcc(), "ccc");
        
        $this->assertEquals($a->getPub_value(), 123);
        $this->assertNull($a->getPro_value());
        $this->assertNull($a->getPri_value());
        
        $a->setPub_value("098");
        $a->setPro_value("765");
        $a->setPri_value("4321");
        
        $this->assertEquals($a->getPub_value(), "098");
        $this->assertEquals($a->getPro_value(), "765");
        $this->assertEquals($a->getPri_value(), "4321");
        
        $b = $container->getComponent("b");
        $this->assertNotNull($b);
        $refB = new ReflectionClass(get_class($b));
        $this->assertTrue($refB->hasMethod("serialize"));
        $this->assertTrue($refB->hasMethod("unserialize"));
    }*/
}

interface IA_S2Container_XmlS2ContainerBuilder{}
class A_S2Container_XmlS2ContainerBuilder
    implements IA_S2Container_XmlS2ContainerBuilder{
    public function __construct(){}
} 

interface IB_S2Container_XmlS2ContainerBuilder{}
class B_S2Container_XmlS2ContainerBuilder
    extends A_S2Container_XmlS2ContainerBuilder
    implements IB_S2Container_XmlS2ContainerBuilder{
    function __construct() {
        parent::__construct();
    }
}

class C_S2Container_XmlS2ContainerBuilder {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}

interface IG_S2Container_XmlS2ContainerBuilder{}
class D_S2Container_XmlS2ContainerBuilder implements IG_S2Container_XmlS2ContainerBuilder{}

class G_S2Container_XmlS2ContainerBuilder 
    implements IG_S2Container_XmlS2ContainerBuilder {

    function finish(){
        print "destroy class G \n";
    }

    function finish2($msg){
        print "$msg G \n";
    }
}

class I_S2Container_XmlS2ContainerBuilder {

    private $result = -1;
    
    function culc(){
        $this->result = 1+1;
    }

    function culc2($a,$b){

        $this->result = $a+$b;
    }

    function culc3(IG_S2Container_XmlS2ContainerBuilder $d){
        if($d instanceof D){
            $this->result = 4;
        }else{return -1;}
    }
    
    function getResult(){
        return $this->result;
    }
}

class L_S2Container_XmlS2ContainerBuilder {
    private $comp;

    function setComp(IG_S2Container_XmlS2ContainerBuilder $comp){
        $this->comp = $comp;
    }

    function getComp(){
        return $this->comp;
    }
}

class M_S2Container_XmlS2ContainerBuilder {
    private $name;
    
    function __set($name,$value){
        print __METHOD__ . " called.\n";
       $this->$name = $value;    
    }

    function getName(){
        return $this->name;
    }
}

class N_S2Container_XmlS2ContainerBuilder {

   private $val1;
   private $val2;

   function __construct($val1) {
       $this->val1 = $val1; 
   }

   function getVal1() {
       return $this->val1; 
   }

   function setVal2($val2) {
       $this->val2 = $val2; 
   }

   function getVal2() {
       return $this->val2; 
   }
}

interface IO_S2Container_XmlS2ContainerBuilder {
    function om1();
    function om2();
}
class O_S2Container_XmlS2ContainerBuilder
    implements IO_S2Container_XmlS2ContainerBuilder {
    function om1() {
    }

    function om2() {
    }

    function om3() {
    }
}

class R_S2Container_XmlS2ContainerBuilder {
 
    private $val1;
    private $val2;
    
    function __construct($val1,$val2) {
        $this->val1 = $val1;
        $this->val2 = $val2;
    }
    
    function setVal1($val1){
        $this->val1 = $val1;
    }

    function setVal2($val2){
        $this->val2 = $val2;
    }

    function getVal1(){
        return $this->val1;
    }

    function getVal2(){
        return $this->val2;
    }

    function finish($msg){
        print "destroy method called. arg = $msg\n";
    }
}

class S_S2Container_XmlS2ContainerBuilder {
    private $val1;
    private $val2;
    private $refO;
    private $refG;
    
    function __construct($val1="",IO_S2Container_XmlS2ContainerBuilder $o) {
        $this->val1 = $val1;
        $this->refO = $o;
    }
    
    function getRefO(){
        return $this->refO;
    }    

    function setRefG(IG_S2Container_XmlS2ContainerBuilder $g){
        $this->refG = $g;
    }    
    function getRefG(){
        return $this->refG;
    }    

    function getVal1(){
        return $this->val1;
    }    

    function setVal2($val2=""){
        $this->val2 = $val2;
    }    
    function getVal2(){
        return $this->val2;
    }    
}

class V_S2Container_XmlS2ContainerBuilder {

    private $container;

    function setContainer(S2Container $container){
        $this->container = $container;
    }
    
    function getContainer(){
        return $this->container;
    }
}

interface IDelegateA_S2Container_XmlS2ContainerBuilder {
    function ma();
    function mc();    
}
class DelegateA_S2Container_XmlS2ContainerBuilder
    implements IDelegateA_S2Container_XmlS2ContainerBuilder {

    function ma(){
        return "ma called.";
    }

    function mc(){
        return "mc called.";
    }
}

class Date_S2Container_XmlS2ContainerBuilder {
    function getTime(){
        return '12:00:30';
    }

    function getDay(){
        return '25';
    }
}

interface ICalculator_S2Container_XmlS2ContainerBuilder {
   public function add($a,$b);
   public function sub($a,$b);
   public function div($a,$b);
}
class CalculatorImpl_S2Container_XmlS2ContainerBuilder
    implements ICalculator_S2Container_XmlS2ContainerBuilder {
   public function add($a,$b) {
       return $a+$b;
   }
   public function sub($a,$b) {
       return $a-$b;
   }

   public function div($a,$b) {
       return $a/$b;
   }
}

interface IAddAction_S2Container_XmlS2ContainerBuilder {
   public function add($a,$b);
}
class AddActionImpl_S2Container_XmlS2ContainerBuilder
    implements IAddAction_S2Container_XmlS2ContainerBuilder {
   
   private $logic;
   
   public function __construct(ICalculator_S2Container_XmlS2ContainerBuilder $logic) {
       $this->logic = $logic;
   }
   
   public function add($a,$b) {
       return $this->logic->add($a,$b);
   }
}

interface ISubAction_S2Container_XmlS2ContainerBuilder {
   public function sub($a,$b);
}

class SubActionImpl_S2Container_XmlS2ContainerBuilder
    implements ISubAction_S2Container_XmlS2ContainerBuilder {
   
   private $logic;
   
   public function setLogic(ICalculator_S2Container_XmlS2ContainerBuilder $logic) {
       $this->logic = $logic;
   }
   
   public function sub($a,$b) {
       return $this->logic->sub($a,$b);
   }
}

class A_InterType_S2Container_XmlS2ContainerBuilder {
    public $aaa;
    protected $bbb;
    private $ccc;
    public $pub_value = 123;
    protected $pro_value = 456;
    private $pri_value = 789;
}

class B_InterType_S2Container_XmlS2ContainerBuilder {
    public $aaa;
    protected $bbb;
    private $ccc;
}
?>
