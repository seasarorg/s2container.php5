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
 * @file S2Container_AutoBindingUtilTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.util
 * @class S2Container_AutoBindingUtilTest
 */
class AutoBindingUtilTests extends PHPUnit2_Framework_TestCase {

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
     * testMode
     * @return 
     */
    public function testMode() {

        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('auto'));
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('AUTO'));
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('auTo'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isAuto('au'));

        $this->assertTrue(S2Container_AutoBindingUtil::isConstructor('constructor'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isConstructor('con'));

        $this->assertTrue(S2Container_AutoBindingUtil::isProperty('property'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isProperty('prop'));

        $this->assertTrue(S2Container_AutoBindingUtil::isNone('none'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isNone('no'));
    }
            
    /**
     * testIsSuitable
     * @return 
     */
    public function testIsSuitable() {

        $this->assertTrue(S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('IA')));
        $this->assertTrue(!S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('A')));

        $res = array(new ReflectionClass('IA'),
                      new ReflectionClass('IB'));
        $this->assertTrue(S2Container_AutoBindingUtil::isSuitable($res));

        $res = array(new ReflectionClass('A'),
                      new ReflectionClass('IB'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isSuitable($res));
    }
}
?>