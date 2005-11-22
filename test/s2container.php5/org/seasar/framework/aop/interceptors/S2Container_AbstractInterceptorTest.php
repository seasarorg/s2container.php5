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
 * @file S2Container_AbstractInterceptorTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.aop.interceptors
 * @class S2Container_AbstractInterceptorTest
 */
class AbstractInterceptorTests extends PHPUnit2_Framework_TestCase {

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

/*
    function testCreateProxy() {
       
        $interceptor = new S2Container_TraceInterceptor();
        $proxy = $interceptor->createProxy(new ReflectionClass('I'));
        $proxy->culc();
        $this->assertEquals($proxy->getResult(),2);
    }
*/
    function testGetTargetClass() {
       
        $interceptor = new TestInterceptor();
        $proxy = $interceptor->createProxy(new ReflectionClass('I'));
        $proxy->culc();
        $this->assertType('ReflectionClass', $interceptor->getClazz());
    }
            
    /**
     * testGetComponentDef
     * @return 
     */
    public function testGetComponentDef() {
       
        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $interceptor = new TestInterceptor();
        $aspect = new S2Container_AspectImpl($interceptor, $pointcut);
        $params[S2Container_ContainerConstants::COMPONENT_DEF_NAME] = new S2Container_ComponentDefImpl('Date','date');
        $aopProxy = new S2Container_AopProxy('Date', array($aspect),$params);
        $proxy = $aopProxy->create();
        $proxy->getTime();
        $cd = $interceptor->getCd();
        
        $this->assertType('S2Container_ComponentDefImpl', $cd);
    }

}

class TestInterceptor extends S2Container_AbstractInterceptor {
    private $clazz;
    private $cd;
    
    public function invoke(S2Container_MethodInvocation $invocation){
	    $this->clazz = $this->getTargetClass($invocation);
	    $this->cd = $this->getComponentDef($invocation);
    }
            
    /**
     * getClazz
     * @return 
     */
    public function getClazz(){
        return $this->clazz;	
    }
            
    /**
     * getCd
     * @return 
     */
    public function getCd(){
        return $this->cd;	
    }
}

?>