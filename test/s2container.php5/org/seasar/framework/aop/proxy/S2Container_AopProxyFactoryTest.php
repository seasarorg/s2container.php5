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
 * @file S2Container_AopProxyFactoryTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.aop.proxy
 * @class S2Container_AopProxyFactoryTest
 */
class AopProxyFactoryTests extends PHPUnit2_Framework_TestCase {

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
     * testAopProxyFactory
     * @return 
     */
    public function testAopProxyFactory() {

        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW'),
                                            new S2Container_TraceInterceptor());
       
        $c = S2Container_AopProxyFactory::create(new WextendAW(),
                                                 new ReflectionClass('WextendAW'),
                                                 array($ad->getAspect()),
                                                 array());
        if($c instanceof IW){
            $this->assertTrue(true);
            $c->awm1();
        }else{
            $this->assertTrue(false);
        }              
              
        $c = S2Container_AopProxyFactory::create(null,
                                                 new ReflectionClass('IW'),
                                                 array($ad->getAspect()),
                                                 array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
          
        $c = S2Container_AopProxyFactory::create(new WextendAW(),
                                                 null,
                                                 array($ad->getAspect()),
                                                 array());
        if($c instanceof IW){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }
            
    /**
     * testNullTarget
     * @return 
     */
    public function testNullTarget() {

        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW'),
                                            new S2Container_TraceInterceptor());

        try{
            $c = S2Container_AopProxyFactory::create(null,
                                                     null,
                                                     array($ad->getAspect()),
                                                     array());
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException', $e);
        }
    }
            
    /**
     * testNullAspect
     * @return 
     */
    public function testNullAspect() {

        try{
            $c = S2Container_AopProxyFactory::create(new WextendAW(),
                                                     null,
                                                     array(),
                                                     array());
        }catch(Exception $e){
            $this->assertType('S2Container_EmptyRuntimeException', $e);
        }
    }
}
?>
