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
 * @package org.seasar.framework.container.impl
 */
/**
 * @file S2Container_S2ContainerImplTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.impl
 * @class S2Container_S2ContainerImplTest
 */
class S2ContainerImplTests extends PHPUnit2_Framework_TestCase {

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
     * testInstatiation
     * @return 
     */
    public function testInstatiation() {
       
        $container = new S2ContainerImpl();
        $this->assertNotNull($container);
    }
            
    /**
     * testRegister1
     * @return 
     */
    public function testRegister1() {
       
        $container = new S2ContainerImpl();
        $container->register(new B(),'b');
        $bdef = $container->getComponentDef('b');
        $b = $container->getComponent('b');
        $this->assertNotNull($container);
    }
            
    /**
     * testRegister2
     * @return 
     */
    public function testRegister2() {
       
        $container = new S2ContainerImpl();
        $container->register('B','b');
          
        $bdef = $container->getComponentDef('b');
        $this->assertNotNull($container);
    }
            
    /**
     * testRegister3
     * @return 
     */
    public function testRegister3() {
       
        $container = new S2ContainerImpl();
        $container->register('D','d1');
        $container->register('D','d2');
          
        $d1 = $container->getComponent('d1');

        $this->assertType('D', $d1);
    }
            
    /**
     * testRegister4
     * @return 
     */
    public function testRegister4() {
       
        $container = new S2ContainerImpl();
        $container->register('L','l');
          
        $l = $container->getComponent('l');

        $this->assertType('L', $l);
    }
            
    /**
     * testTooManyRegister
     * @return 
     */
    public function testTooManyRegister() {
       
        $container = new S2ContainerImpl();
        $container->register('D');
        $container->register('D');
        $ddef = $container->getComponentDef('D');
          
        try{
            $d = $container->getComponent('D');
        }catch(Exception $e){
            $this->assertType('S2Container_TooManyRegistrationRuntimeException', $e);
        }
    }
            
    /**
     * testCompNotFound
     * @return 
     */
    public function testCompNotFound() {
       
        $container = new S2ContainerImpl();
  
        try{
            $d = $container->getComponent('D');
        }catch(Exception $e){
            $this->assertType('S2Container_ComponentNotFoundRuntimeException', $e);
            $this->assertEquals($e->getComponentKey(),'D');
        }
    }
            
    /**
     * testCyclicRef
     * @return 
     */
    public function testCyclicRef() {
       
        $container = new S2ContainerImpl();
        $container->register('J');
        $container->register('K');
  
        try{
            $d = $container->getComponent('J');
        }catch(Exception $e){
            $this->assertType('S2Container_CyclicReferenceRuntimeException', $e);
        }
    }
            
    /**
     * testNamesapce
     * @return 
     */
    public function testNamesapce() {
       
        $container = new S2ContainerImpl();
        $container->setNamespace('nemu');
        $container->register('D');
  
        $c = $container->getComponent('nemu');
        $this->assertSame($c,$container);
    }
            
    /**
     * testChildConWithNamespace
     * @return 
     */
    public function testChildConWithNamespace() {
       
        $root = new S2ContainerImpl();
        $root->setNamespace('ro');
        $child = new S2ContainerImpl();
        $child->setNamespace('ch');
        $child->register('D');
  
        $root->includeChild($child);
       
        try{
            $d = $root->getComponent('D');
        }catch(Exception $e){
            $this->assertType('S2Container_ComponentNotFoundRuntimeException', $e);
            $this->assertEquals($e->getComponentKey(),'D');
        }

        $ch = $root->getComponent('ch');
        $this->assertSame($ch,$child);
       
        $d = $root->getComponent('ch.D');
        $this->assertType('D', $d);
    }
            
    /**
     * testChildCon
     * @return 
     */
    public function testChildCon() {
       
        $root = new S2ContainerImpl();
        $child1 = new S2ContainerImpl();
        $child1->register('D');
        $child2 = new S2ContainerImpl();
  
        $root->includeChild($child1);
        $root->includeChild($child2);
       
        $d = $root->getComponent('D');
        $this->assertType('D', $d);
    }
            
    /**
     * testSample
     * @return 
     */
    public function testSample() {
       
        $container = new S2ContainerImpl();
        $container->register('N','n');
          
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
            
    /**
     * testGetComponentDef
     * @return 
     */
    public function testGetComponentDef() {
       
        $container = new S2ContainerImpl();
        $container->register('B','b');
        $def = $container->getComponentDef(new B());
        $this->assertType('S2Container_ComponentDefImpl', $def);

        $container->register('B','bb');
        $def = $container->getComponentDef(0);
        $this->assertType('S2Container_ComponentDefImpl', $def);
        $def = $container->getComponentDef(1);
        $this->assertType('S2Container_ComponentDefImpl', $def);
          
        try{
            $def = $container->getComponentDef(2);
            $this->assertType('S2Container_ComponentDefImpl', $def);
        }catch(Exception $e){
            $this->assertType('S2Container_ComponentNotFoundRuntimeException', $e);
        }
    }
            
    /**
     * testGetComponentDef
     * @return 
     */
    public function testGetComponentDefSize() {

        $container = new S2ContainerImpl();
        $container->register('A','a');
        $container->register('b','b');

        $this->assertEquals($container->getComponentDefSize(),2);
    }
            
    /**
     * testDescendant
     * @return 
     */
    public function testDescendant() {

        $dc = new S2ContainerImpl();
        $dc->setPath('d:/hoge.dicon');
           
        $container = new S2ContainerImpl();
        $container->registerDescendant($dc);

        $this->assertTrue($container->hasDescendant($dc->getPath()));
       
        $dc2 = $container->getDescendant($dc->getPath());
        $this->assertSame($dc,$dc2);
    }
            
    /**
     * testMetaDefSupport
     * @return 
     */
    public function testMetaDefSupport() {

        $container = new S2ContainerImpl();
               
        $meta1 = new S2Container_MetaDefImpl('a');
        $meta2 = new S2Container_MetaDefImpl('b');
        $meta3 = new S2Container_MetaDefImpl('c');
        
        $container->addMetaDef($meta1);
        $container->addMetaDef($meta2);
        $container->addMetaDef($meta3);
        
        $this->assertEquals($container->getMetaDefSize(),3);
        $meta = $container->getMetaDef(1);
        $this->assertSame($meta,$meta2);

        $meta = $container->getMetaDef('c');
        $this->assertSame($meta,$meta3);
    }
            
    /**
     * testNoChildIndex
     * @return 
     */
    public function testNoChildIndex() {

        $container = new S2ContainerImpl();

        try{
            $container->getChild(2);	
        }catch(Exception $e){   
        	$this->assertType('S2Container_ContainerNotRegisteredRuntimeException', $e);
        }
    }
            
    /**
     * testFindComponents
     * @return 
     */
    public function testFindComponents() {

        $container = new S2ContainerImpl();
        $components = $container->findComponents('A');
        $this->assertEquals(count($components),0);

        $container->register(new A(),'a');

        $components = $container->findComponents('A');
        $this->assertEquals(count($components),1);
        $this->assertType('A', $components[0]);

        $container->register('D','A');
        $components = $container->findComponents('A');
        $this->assertEquals(count($components),2);
        $this->assertType('A', $components[0]);
        $this->assertType('D', $components[1]);
    }
            
    /**
     * testReconstruct
     * @return 
     */
    public function testReconstruct() {

        $container = new S2ContainerImpl();
        $container->register(new A(),'a');

        $refA1 = $container->getComponentDef('a')->getComponentClass();
        $container->reconstruct();

        $refA2 = $container->getComponentDef('a')->getComponentClass();
        $this->assertSame($refA1,$refA2);

        $container->reconstruct(S2Container_ComponentDef::RECONSTRUCT_FORCE);

        $refA2 = $container->getComponentDef('a')->getComponentClass();
        if($refA1===$refA2){
            $this->assertTrue(false);	        	
        }else{
            $this->assertTrue(true);	        	
        }
    }
}
?>