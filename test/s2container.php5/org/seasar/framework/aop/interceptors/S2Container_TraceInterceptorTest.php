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
 * @file S2Container_TraceInterceptorTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.aop.interceptors
 * @class S2Container_TraceInterceptorTest
 */
class TraceInterceptorTests extends PHPUnit2_Framework_TestCase {

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
     * testTraceInterceptor
     * @return 
     */
    public function testTraceInterceptor() {
       
        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $aopProxy = new S2Container_AopProxy('Date', array($aspect));
        $proxy = $aopProxy->create();
        $this->assertEquals($proxy->getTime(),'12:00:30');
    }
            
    /**
     * testArgs
     * @return 
     */
    public function testArgs() {
       
        $pointcut = new S2Container_PointcutImpl(array("culc2"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $aopProxy = new S2Container_AopProxy('I', array($aspect));
        $proxy = $aopProxy->create();
        $proxy->culc2(4,8);
        $this->assertEquals($proxy->getResult(),12);
    }
}
?>