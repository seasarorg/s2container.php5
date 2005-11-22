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
 * @package org.seasar.framework.util
 */
/**
 * @file S2Container_EvalUtilTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.util
 * @class S2Container_EvalUtilTest
 */
class EvalUtilTests extends PHPUnit2_Framework_TestCase {

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
     * testGetExpression
     * @return 
     */
    public function testGetExpression() {
       
        $exp = "100";
        $ret = "return 100;";
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEquals($ret,$result);

        $exp = '"hoge"';
        $ret = 'return "hoge";';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEquals($ret,$result);

        $exp = 'return "hoge"';
        $ret = 'return "hoge";';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEquals($ret,$result);

        $exp = 'return 1000';
        $ret = 'return 1000;';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEquals($ret,$result);

        $exp = '1000;';
        $ret = 'return 1000;';
        $result = S2Container_EvalUtil::getExpression($exp);
        $this->assertEquals($ret,$result);
    }
            
    /**
     * testAddSemiColon
     * @return 
     */
    public function testAddSemiColon() {

        $exp = '1000';
        $ret = '1000;';
        $result = S2Container_EvalUtil::addSemiColon($exp);
        $this->assertEquals($ret,$result);

        $exp = '1000;';
        $ret = '1000;';
        $result = S2Container_EvalUtil::addSemiColon($exp);
        $this->assertEquals($ret,$result);
    }
}
?>