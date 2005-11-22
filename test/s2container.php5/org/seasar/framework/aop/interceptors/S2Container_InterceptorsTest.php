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
 * @package org.seasar.framework.aop.interceptors
 */
/**
 * @file S2Container_InterceptorsTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.aop.interceptors
 * @class S2Container_InterceptorsTest
 */
class InterceptorsTests extends PHPUnit2_Framework_TestCase {

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
     * testTraceThrowsInterceptor
     * @return 
     */
    public function testTraceThrowsInterceptor() {
       
        $pointcut = new S2Container_PointcutImpl(array("throwE"));
       
        $tt = new S2Container_TraceThrowsInterceptor();
        $aspect = new S2Container_AspectImpl($tt, $pointcut);
        $aopProxy = new S2Container_AopProxy('Q', array($aspect));
        $proxy = $aopProxy->create();
        try{
            $proxy->throwE();
        }catch(Exception $e){
            $this->assertType('S2Container_UnsupportedOperationException', $e);
        }
    }
            
    /**
     * testInterceptorChain
     * @return 
     */
    public function testInterceptorChain() {
       
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
        $chain = new S2Container_InterceptorChain();
        $chain->add(new S2Container_TraceInterceptor());
        $chain->add(new S2Container_MockInterceptor('pm1','mock value.'));
        $aspect = new S2Container_AspectImpl($chain, $pointcut);
        $aopProxy = new S2Container_AopProxy('P', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEquals($proxy->pm1(),'mock value.');
    }
            
    /**
     * testPrototypeDelegateInterceptor
     * @return 
     */
    public function testPrototypeDelegateInterceptor() {
       
        $container = new S2ContainerImpl();
        $container->register('DelegateB','b');
          
        $cd = $container->getComponentDef('b');
        $cd->setInstanceMode(S2Container_ContainerConstants::INSTANCE_PROTOTYPE);
          
        $pointcut = new S2Container_PointcutImpl(array("ma"));
        $proto = new S2Container_PrototypeDelegateInterceptor($container);
        $proto->setTargetName('b');
        $proto->addMethodNameMap('ma','mb');
        $aspect = new S2Container_AspectImpl($proto, $pointcut);
        $aopProxy = new S2Container_AopProxy('DelegateA', array($aspect));
        $proxy = $aopProxy->create();

        $this->assertEquals($proxy->ma(),'mb called.');
    }
            
    /**
     * testTraceAndMock
     * @return 
     */
    public function testTraceAndMock() {
       
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
       
        $taspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $mock = new S2Container_MockInterceptor('pm1','mock value.');
        $aspect = new S2Container_AspectImpl($mock, $pointcut);
        $aopProxy = new S2Container_AopProxy('P', array($taspect,$aspect));
        $proxy = $aopProxy->create();
        $this->assertEquals($proxy->pm1(),'mock value.');
    }
            
    /**
     * testMockInterceptor
     * @return 
     */
    public function testMockInterceptor() {
       
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
       
        $mock = new S2Container_MockInterceptor('pm1','mock value.');
        $aspect = new S2Container_AspectImpl($mock, $pointcut);
        $aopProxy = new S2Container_AopProxy('IP', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEquals($proxy->pm1(),'mock value.');
    }
            
    /**
     * testTraceInterceptorContaienr
     * @return 
     */
    public function testTraceInterceptorContaienr() {

        $container = new S2ContainerImpl();
        $container->register('Date','d');
        $cd = $container->getComponentDef('d');

        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $aspectDef = new S2Container_AspectDefImpl(new S2Container_TraceInterceptor(), $pointcut);
        $cd->addAspectDef($aspectDef);
        $d = $container->getComponent('d');

        $this->assertEquals($d->getTime(),'12:00:30');
    }
            
    /**
     * testUuCallMethod
     * @return 
     */
    public function testUuCallMethod() {
       
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(),new S2Container_PointcutImpl(array("getMessage")));
        $aopProxy = new S2Container_AopProxy('X', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEquals($proxy->getMessage(),'hello');
    }
            
    /**
     * testNoMethodInterface
     * @return 
     */
    public function testNoMethodInterface() {
       
        $pointcut = new S2Container_PointcutImpl('IA');
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $aopProxy = new S2Container_AopProxy('IA', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertNotNull($proxy);
    }
            
    /**
     * testFinalClassAspect
     * @return 
     */
    public function testFinalClassAspect() {
       
        $pointcut = new S2Container_PointcutImpl('Y');
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $aopProxy = new S2Container_AopProxy('Y', array($aspect));
        try{
            $proxy = $aopProxy->create();
        }catch(Exception $e){
            if($e instanceof S2Container_S2RuntimeException ){
            	$this->assertTrue(true);
            	print($e->getMessage()."\n");
            }else{
            	$this->assertTrue(false);
            }        	
        }
    }
            
    /**
     * testStaticMethodAspect
     * @return 
     */
    public function testStaticMethodAspect() {
       
        $pointcut = new S2Container_PointcutImpl(array('z1','z2'));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $aopProxy = new S2Container_AopProxy('Z', array($aspect));
       	$this->assertNotNull($aopProxy);
       	$proxy = $aopProxy->create();
        $this->assertTrue($proxy->z2());

        // check generated src at S2Container_UuCallAopProxyFactory : 101
    }
            
    /**
     * testInvokeInterfaceMethod
     * @return 
     */
    public function testInvokeInterfaceMethod() {
       
        $pointcut = new S2Container_PointcutImpl(array('om2'));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $aopProxy = new S2Container_AopProxy('IO', array($aspect));
       	$this->assertNotNull($aopProxy);
       	$proxy = $aopProxy->create();
       	try{
       	    $proxy->om1();	
       	}catch(Exception $e){
       		$this->assertType('S2Container_S2RuntimeException', $e);
       	}
    } 
}
?>