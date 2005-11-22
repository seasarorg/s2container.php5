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
 * @package org.seasar.framework.container.util
 */
/**
 * @file S2Container_InstanceModeUtilTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.util
 * @class S2Container_InstanceModeUtilTest
 */
class InstanceModeUtilTests extends PHPUnit2_Framework_TestCase {

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

    private $a = array();
    function testArray() {
       
        $this->a[0] = new A();
        $this->a[1] = new B();
        $this->a[2] = new A();
        $this->a[3] = new B();
       
        $j = 0;
        foreach($this->a as $i){
            $k = $this->arrayTest($j);
            $this->assertSame($k,$i);
            $j++;
        }
    }
            
    /**
     * arrayTest
     * @param $index
     * @return 
     */
    public function arrayTest($index){
        return $this->a[$index];
    }
            
    /**
     * testIsOuter
     * @return 
     */
    public function testIsOuter() {
       
        $this->assertTrue(S2Container_InstanceModeUtil::isOuter('outer'));
        $this->assertTrue(S2Container_InstanceModeUtil::isOuter('oUter'));
        $this->assertFalse(S2Container_InstanceModeUtil::isOuter('oouter'));
    }
            
    /**
     * testIsSingleton
     * @return 
     */
    public function testIsSingleton() {
       
        $this->assertTrue(S2Container_InstanceModeUtil::isSingleton('singleton'));
        $this->assertTrue(S2Container_InstanceModeUtil::isSingleton('SingleTon'));
        $this->assertFalse(S2Container_InstanceModeUtil::isSingleton('single'));
    }
            
    /**
     * testIsPrototype
     * @return 
     */
    public function testIsPrototype() {
       
        $this->assertTrue(S2Container_InstanceModeUtil::isPrototype('prototype'));
        $this->assertTrue(S2Container_InstanceModeUtil::isPrototype('Prototype'));
        $this->assertFalse(S2Container_InstanceModeUtil::isPrototype('pro'));
    }
            
    /**
     * testIsRequest
     * @return 
     */
    public function testIsRequest() {
       
        $this->assertTrue(S2Container_InstanceModeUtil::isRequest('request'));
        $this->assertTrue(S2Container_InstanceModeUtil::isRequest('Request'));
        $this->assertFalse(S2Container_InstanceModeUtil::isRequest('req'));
    }
            
    /**
     * testIsSession
     * @return 
     */
    public function testIsSession() {
       
        $this->assertTrue(S2Container_InstanceModeUtil::isSession('session'));
        $this->assertTrue(S2Container_InstanceModeUtil::isSession('Session'));
        $this->assertFalse(S2Container_InstanceModeUtil::isSession('ses'));
    }    
}
?>