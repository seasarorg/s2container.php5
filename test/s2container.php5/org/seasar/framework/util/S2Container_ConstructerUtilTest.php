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
 * @file S2Container_ConstructerUtilTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.util
 * @class S2Container_ConstructerUtilTest
 */
class ConstructerUtilTests extends PHPUnit2_Framework_TestCase {

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
     * testParent
     * @return 
     */
    public function testParent() {
       
        $uRef = new ReflectionClass('U');
        $args = array(new D());
        $u = new U($args[0]);
        $uRef->newInstance(new D());
    }
            
    /**
     * testInstance
     * @return 
     */
    public function testInstance() {
       
        $a = S2Container_ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         array());
        $this->assertType('A', $a);

        $a = S2Container_ConstructorUtil::newInstance(new ReflectionClass('A'),
                                         null);
        $this->assertType('A', $a);
    }
            
    /**
     * testInstance
     * @return 
     */
    public function testInstanceWithArgs() {
       
        $c = S2Container_ConstructorUtil::newInstance(new ReflectionClass('C'),
                                         array('hoge'));
        $this->assertType('C', $c);
    }
            
    /**
     * testIllegalRelfection
     * @return 
     */
    public function testIllegalRelfection() {

        try{
            $c = S2Container_ConstructorUtil::newInstance('C',array('hoge'));
        }catch(Exception $e){
            $this->assertType('S2Container_IllegalArgumentException', $e);
        }
    }
}
?>