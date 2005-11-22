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
 * @file S2Container_ClassUtilTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.util
 * @class S2Container_ClassUtilTest
 */
class ClassUtilTests extends PHPUnit2_Framework_TestCase {

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
     * testGetClassSource
     * @return 
     */
    public function testGetClassSource() {
       
        $uRef = new ReflectionClass('U');
        $src = S2Container_ClassUtil::getClassSource($uRef);

        $this->assertEquals(trim($src[0]),"class U{");       
        $this->assertEquals(trim($src[3]),"}");
    }
            
    /**
     * testGetSource
     * @return 
     */
    public function testGetSource() {
        $uRef = new ReflectionClass('U');
        $src = S2Container_ClassUtil::getSource($uRef);
        $this->assertEquals(trim($src[2]),"class U{");       
        $this->assertEquals(trim($src[5]),"}");
    }
            
    /**
     * testGetMethod
     * @return 
     */
    public function testGetMethod() {
       
        $cRef = new ReflectionClass('C');
        $mRef = S2Container_ClassUtil::getMethod($cRef,'say');
        $this->assertEquals($mRef->getName(),"say");       

        try{
            $mRef = S2Container_ClassUtil::getMethod($cRef,'say2');
        }catch(Exception $e){
            $this->assertType('S2Container_NoSuchMethodRuntimeException', $e);	
        }
    }
            
    /**
     * testHasMethod
     * @return 
     */
    public function testHasMethod() {
       
        $cRef = new ReflectionClass('C');
        $this->assertTrue(S2Container_ClassUtil::hasMethod($cRef,'say'));
        $this->assertTrue(!S2Container_ClassUtil::hasMethod($cRef,'say2'));
    }
            
    /**
     * testGetInterfaces
     * @return 
     */
    public function testGetInterfaces() {
       
        $cRef = new ReflectionClass('G');
        $this->assertEquals(count(S2Container_ClassUtil::getInterfaces($cRef)),1);

        $cRef = new ReflectionClass('IW');
        $this->assertEquals(count(S2Container_ClassUtil::getInterfaces($cRef)),2);
    }
}
?>