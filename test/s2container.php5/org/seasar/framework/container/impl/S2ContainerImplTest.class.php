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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2ContainerImplTest
    extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testInstatiation() {
        $container = new S2ContainerImpl();
        $this->assertNotNull($container);
    }

    function testRegister1() {
        $container = new S2ContainerImpl();
        $container->register(new B_S2ContainerImpl(),'b');
        $bdef = $container->getComponentDef('b');
        $b = $container->getComponent('b');
        $this->assertNotNull($container);
    }

    function testRegister2() {
        $container = new S2ContainerImpl();
        $container->register('B_S2ContainerImpl','b');
          
        $bdef = $container->getComponentDef('b');
        $this->assertNotNull($container);
    }

    function testRegister3() {
        $container = new S2ContainerImpl();
        $container->register('D_S2ContainerImpl','d1');
        $container->register('D_S2ContainerImpl','d2');
          
        $d1 = $container->getComponent('d1');

        $this->assertType('D_S2ContainerImpl',$d1);
    }

    function testRegister4() {
        $container = new S2ContainerImpl();
        $container->register('L_S2ContainerImpl','l');
          
        $l = $container->getComponent('l');

        $this->assertType('L_S2ContainerImpl',$l);
    }

    function testTooManyRegister() {
        $container = new S2ContainerImpl();
        $container->register('D_S2ContainerImpl');
        $container->register('D_S2ContainerImpl');
        $ddef = $container->getComponentDef('D_S2ContainerImpl');
          
        try{
            $d = $container->getComponent('D_S2ContainerImpl');
        }catch(Exception $e){
            $this->assertType('S2Container_TooManyRegistrationRuntimeException',$e);
        }
    }

    function testCompNotFound() {
        $container = new S2ContainerImpl();
  
        try{
            $d = $container->getComponent('D_S2ContainerImpl');
        }catch(Exception $e){
            $this->assertType('S2Container_ComponentNotFoundRuntimeException',$e);
            $this->assertEquals($e->getComponentKey(),'D_S2ContainerImpl');
        }
    }
   
    function testCyclicRef() {
        $container = new S2ContainerImpl();
        $container->register('J_S2ContainerImpl');
        $container->register('K_S2ContainerImpl');
  
        try{
            $d = $container->getComponent('J_S2ContainerImpl');
        }catch(Exception $e){
            $this->assertType('S2Container_CyclicReferenceRuntimeException',$e);
        }
    }

    function testNamesapce() {
        $container = new S2ContainerImpl();
        $container->setNamespace('nemu');
        $container->register('D_S2ContainerImpl');
  
        $c = $container->getComponent('nemu');
        $this->assertTrue($c === $container);
    }

    function testChildConWithNamespace() {
        $root = new S2ContainerImpl();
        $root->setNamespace('ro');
        $child = new S2ContainerImpl();
        $child->setNamespace('ch');
        $child->register('D_S2ContainerImpl');
  
        $root->includeChild($child);
       
        try{
            $d = $root->getComponent('D_S2ContainerImpl');
        }catch(Exception $e){
            $this->assertType('S2Container_ComponentNotFoundRuntimeException',$e);
            $this->assertEquals($e->getComponentKey(),'D_S2ContainerImpl');
        }

        $ch = $root->getComponent('ch');
        $this->assertTrue($ch === $child);
       
        $d = $root->getComponent('ch.D_S2ContainerImpl');
        $this->assertType('D_S2ContainerImpl',$d);
    }

    function testChildCon() {
        $root = new S2ContainerImpl();
        $child1 = new S2ContainerImpl();
        $child1->register('D_S2ContainerImpl');
        $child2 = new S2ContainerImpl();
  
        $root->includeChild($child1);
        $root->includeChild($child2);
       
        $d = $root->getComponent('D_S2ContainerImpl');
        $this->assertType('D_S2ContainerImpl',$d);
    }

    function testSample() {
        $container = new S2ContainerImpl();
        $container->register('N_S2ContainerImpl','n');
          
        $cd = $container->getComponentDef('n');
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("aaa");
        $cd->addArgDef($arg);

        $cd->setAutoBindingMode(
            S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('val2','bbb');
        $cd->addPropertyDef($pro);

        $n = $container->getComponent('n');
        $this->assertEquals($n->getVal1(),'aaa');
        $this->assertEquals($n->getVal2(),'bbb');
    }
    
    function testGetComponentDef() {
        $container = new S2ContainerImpl();
        $container->register('B_S2ContainerImpl','b');
        $def = $container->getComponentDef(new B_S2ContainerImpl());
        $this->assertType('S2Container_ComponentDefImpl',$def);

        $container->register('B_S2ContainerImpl','bb');
        $def = $container->getComponentDef(0);
        $this->assertType('S2Container_ComponentDefImpl',$def);
        $def = $container->getComponentDef(1);
        $this->assertType('S2Container_ComponentDefImpl',$def);
          
        try{
            $def = $container->getComponentDef(2);
            $this->assertType('S2Container_ComponentDefImpl',$def);
        }catch(Exception $e){
            $this->assertType('S2Container_ComponentNotFoundRuntimeException',$e);
        }
    }   

    function testGetComponentDefSize() {
        $container = new S2ContainerImpl();
        $container->register('A_S2ContainerImpl','a');
        $container->register('B_S2ContainerImpl','b');

        $this->assertEquals($container->getComponentDefSize(),2);
    } 
        
    function testDescendant() {
        $dc = new S2ContainerImpl();
        $dc->setPath('d:/hoge.dicon');
           
        $container = new S2ContainerImpl();
        $container->registerDescendant($dc);

        $this->assertTrue($container->hasDescendant($dc->getPath()));
       
        $dc2 = $container->getDescendant($dc->getPath());
        $this->assertTrue($dc === $dc2);
    }     

    function testMetaDefSupport() {
        $container = new S2ContainerImpl();
               
        $meta1 = new S2Container_MetaDefImpl('a');
        $meta2 = new S2Container_MetaDefImpl('b');
        $meta3 = new S2Container_MetaDefImpl('c');
        
        $container->addMetaDef($meta1);
        $container->addMetaDef($meta2);
        $container->addMetaDef($meta3);
        
        $this->assertEquals($container->getMetaDefSize(),3);
        $meta = $container->getMetaDef(1);
        $this->assertTrue($meta === $meta2);

        $meta = $container->getMetaDef('c');
        $this->assertTrue($meta === $meta3);
    } 

    function testNoChildIndex() {
        $container = new S2ContainerImpl();

        try{
            $container->getChild(2);    
        }catch(Exception $e){   
            $this->assertType('S2Container_ContainerNotRegisteredRuntimeException',$e);
            print $e->getMessage() . "\n";
        }            
    } 

    function testFindComponents() {
        $container = new S2ContainerImpl();
        $components = $container->findComponents('A_S2ContainerImpl');
        $this->assertEquals(count($components),0);

        $container->register(new A_S2ContainerImpl(),'a');

        $components = $container->findComponents('A_S2ContainerImpl');
        $this->assertEquals(count($components),1);
        $this->assertType('A_S2ContainerImpl',$components[0]);

        $container->register('D_S2ContainerImpl','A_S2ContainerImpl');
        $components = $container->findComponents('A_S2ContainerImpl');
        $this->assertEquals(count($components),2);
        $this->assertType('A_S2ContainerImpl',$components[0]);
        $this->assertType('D_S2ContainerImpl',$components[1]);
    } 

    function testReconstruct() {
        $container = new S2ContainerImpl();
        $container->register(new A_S2ContainerImpl(),'a');

        $refA1 = $container->getComponentDef('a')->getComponentClass();
        $container->reconstruct();

        $refA2 = $container->getComponentDef('a')->getComponentClass();
        $this->assertTrue($refA1 === $refA2);

        $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);

        $refA2 = $container->getComponentDef('a')->getComponentClass();
        if($refA1===$refA2){
            $this->assertTrue(false);               
        }else{
            $this->assertTrue(true);                
        }        
    }

}

interface IA_S2ContainerImpl {}

interface IB_S2ContainerImpl {}

class A_S2ContainerImpl
    implements IA_S2ContainerImpl {
    public function __construct(){}
}

class B_S2ContainerImpl 
    extends A_S2ContainerImpl
    implements IB_S2ContainerImpl{

    function __construct() {
        parent::__construct();
    }
}

interface IG_S2ContainerImpl{}
class D_S2ContainerImpl implements IG_S2ContainerImpl{}

class L_S2ContainerImpl {
    private $comp;
    function setComp(IG_S2ContainerImpl $comp){
        $this->comp = $comp;
    }

    function getComp(){
        return $this->comp;
    }
}

interface IJ_S2ContainerImpl {}
class J_S2ContainerImpl implements IJ_S2ContainerImpl{
    function __construct(IK_S2ContainerImpl $k) {}
}

interface IK_S2ContainerImpl {}
class K_S2ContainerImpl implements IK_S2ContainerImpl{

    function __construct(IJ_S2ContainerImpl $j) {}
    
    function finish(){
        print "destroy class K.\n";    
    }
}


class N_S2ContainerImpl {
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
?>
