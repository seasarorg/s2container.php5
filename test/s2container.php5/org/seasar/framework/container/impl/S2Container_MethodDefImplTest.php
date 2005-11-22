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
 * @file S2Container_MethodDefImplTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.container.impl
 * @class S2Container_MethodDefImplTest
 */
class MethodDefImplTests extends PHPUnit2_Framework_TestCase {

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
     * testArgDef
     * @return 
     */
    public function testArgDef() {
       
        $arg1 = new S2Container_ArgDefImpl('a');
        $arg2 = new S2Container_ArgDefImpl('b');
        $arg3 = new S2Container_ArgDefImpl('c');

        $im = new S2Container_InitMethodDefImpl('hoge');
        $im->addArgDef($arg1);
        $im->addArgDef($arg2);
        $im->addArgDef($arg3);

        $this->assertEquals($im->getArgDefSize(),3);

        $arg = $im->getArgDef(1);
        $this->assertSame($arg,$arg2);

        $args = $im->getArgs();
        $this->assertEquals($args,array('a','b','c'));
    } 
}
?>