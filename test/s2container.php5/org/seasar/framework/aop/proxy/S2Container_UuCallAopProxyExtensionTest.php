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
 * @package org.seasar.framework.aop.proxy
 */
/**
 * @file S2Container_UuCallAopProxyExtensionTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.aop.proxy
 * @class S2Container_UuCallAopProxyExtensionTest
 */
class UuCallAopProxyExtensionTests extends PHPUnit2_Framework_TestCase {

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
     * testGetInterfaces
     * @return 
     */
    public function testGetInterfaces() {
       
        $ref = new ReflectionClass(new WextendAW());
        $pref = $ref->getParentClass();
        $met =  $pref->getMethod('awm1');
        $this->assertTrue($met->isAbstract());
    }
            
    /**
     * testAopProxyFactory
     * @return 
     */
    public function testAopProxyFactory() {
       
        $c = S2Container_UuCallAopProxyFactory::create(new ReflectionClass(new WextendAW()),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
              
        $c = S2Container_UuCallAopProxyFactory::create(new ReflectionClass('IW'),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }
            
    /**
     * testAopProxyFactory
     * @return 
     */
    public function testAopProxyFactory2() {
       
        $c = S2Container_UuCallAopProxyFactory::create(new ReflectionClass(new WextendAW()),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
              
        $c = S2Container_UuCallAopProxyFactory::create(new ReflectionClass(new WextendAW()),array(),array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }
   
}
?>
