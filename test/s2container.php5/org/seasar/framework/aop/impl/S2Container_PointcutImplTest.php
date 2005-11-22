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
 * @package org.seasar.framework.aop.impl
 */
/**
 * @file S2Container_PointcutImplTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.aop.impl
 * @class S2Container_PointcutImplTest
 */
class PointcutImplTests extends PHPUnit2_Framework_TestCase {

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
     * testNullConstructArg
     * @return 
     */
    public function testNullConstructArg() {

        try{
        	$pc = new S2Container_PointcutImpl();       
        }catch(Exception $e){
        	$this->assertType('S2Container_EmptyRuntimeException', $e);
        }

        try{
        	$pc = new S2Container_PointcutImpl(array());       
        }catch(Exception $e){
        	$this->assertType('S2Container_EmptyRuntimeException', $e);
        }
    }
            
    /**
     * testIsApplied
     * @return 
     */
    public function testIsApplied() {
       
        $pc = new S2Container_PointcutImpl(array('pm1','pm2','om1','om2'));
        $this->assertTrue($pc->isApplied('pm1'));       
        $this->assertTrue($pc->isApplied('pm2'));       
        $this->assertTrue($pc->isApplied('om1'));       
        $this->assertTrue($pc->isApplied('om2'));       

        $pc = new S2Container_PointcutImpl(new ReflectionClass('AW'));
        $this->assertTrue($pc->isApplied('wm1'));       
        $this->assertTrue($pc->isApplied('wm2'));       
        $this->assertTrue($pc->isApplied('om1'));       
        $this->assertTrue($pc->isApplied('om2'));       
        $this->assertTrue($pc->isApplied('awm1'));       

        $pc = new S2Container_PointcutImpl(new ReflectionClass('C'));
        $this->assertTrue($pc->isApplied('say') == false);       

        $pc = new S2Container_PointcutImpl(array('^a','b$'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('deb'));       
        $this->assertTrue($pc->isApplied('om') == false);       

        $pc = new S2Container_PointcutImpl(array('^(!?a)'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertFalse($pc->isApplied('deb'));       
        $this->assertFalse($pc->isApplied('om'));       

        $pc = new S2Container_PointcutImpl(array('(!?a)$'));
        $this->assertFalse($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('aba'));
    }

}
?>
