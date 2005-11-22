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
 * @file S2Container_MethodUtilTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.util
 * @class S2Container_MethodUtilTest
 */
class MethodUtilTests extends PHPUnit2_Framework_TestCase {

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
     * testGetSource
     * @return 
     */
    public function testGetSource() {
       
        $c = new ReflectionClass('C');
        $src = S2Container_ClassUtil::getSource($c);
        $m = $c->getMethod('say');
        $src = S2Container_MethodUtil::getSource($m,$src);

        $this->assertEquals(trim($src[0]),"public function say(){");       
        $this->assertEquals(trim($src[2]),"}");     

        $src = S2Container_MethodUtil::getSource($m);
        $this->assertEquals(trim($src[0]),"public function say(){");       
        $this->assertEquals(trim($src[2]),"}");     

        $ref = new ReflectionClass('IW');
        $src = S2Container_ClassUtil::getSource($ref);
        $m = $ref->getMethod('wm1');
        $src = S2Container_MethodUtil::getSource($m,$src);
        $this->assertEquals(trim($src[0]),'function wm1($arg1,IA &$a);');
    }
            
    /**
     * testInvoke
     * @return 
     */
    public function testInvoke() {

        $t = new MethodUtilTest();
        $ref = new ReflectionClass('MethodUtilTest');
        
        $m = $ref->getMethod('a');
        $ret = S2Container_MethodUtil::invoke($m,$t,array());
        $this->assertTrue($ret);

        $m = $ref->getMethod('a');
        $ret = S2Container_MethodUtil::invoke($m,$t,null);
        $this->assertTrue($ret);

        $m = $ref->getMethod('b');
        $ret = S2Container_MethodUtil::invoke($m,$t,array('hoge'));
        $this->assertEquals($ret,'hoge');

        $m = $ref->getMethod('c');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(1,2));
        $this->assertEquals($ret,3);

        $m = $ref->getMethod('d');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(array(1,2)));
        $this->assertEquals($ret,3);

        $m = $ref->getMethod('e');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(array(1,2)));
        $this->assertEquals($ret,3);

        $m = $ref->getMethod('f');
        $ret = S2Container_MethodUtil::invoke($m,$t,array(array(new A(),new B())));
        $this->assertType('B', $ret);
    }
            
    /**
     * testIllegalRelfection
     * @return 
     */
    public function testIllegalRelfection() {

        try{
        	$ret = S2Container_MethodUtil::invoke(null,new A());
        }catch(Exception $e){
            $this->assertType('S2Container_IllegalArgumentException', $e);
        }
    }
            
    /**
     * testIllegalObject
     * @return 
     */
    public function testIllegalObject() {

        $t = new MethodUtilTest();
        $ref = new ReflectionClass('MethodUtilTest');
        $m = $ref->getMethod('a');

        try{
        	$ret = S2Container_MethodUtil::invoke($m,null);
        }catch(Exception $e){
            $this->assertType('S2Container_IllegalArgumentException', $e);
        }
    }
}

class MethodUtilTest {

    function MethodUtilTest(){}
            
    /**
     * a
     * @return 
     */
    public function a(){
	    return true;	
	}
            
    /**
     * b
     * @param $arg1
     * @return 
     */
    public function b($arg1){
	    return $arg1;	
	}
            
    /**
     * c
     * @param $arg1
     * @param $arg2
     * @return 
     */
    public function c($arg1,$arg2){
	    return $arg1 + $arg2;	
	}
            
    /**
     * d
     * @param $arg
     * @return 
     */
    public function d($arg){
	    return $arg[0] + $arg[1];	
	}
            
    /**
     * e
     * @param &$arg
     * @return 
     */
    public function e(&$arg){
	    return $arg[0] + $arg[1];	
	}
            
    /**
     * f
     * @param &$arg
     * @return 
     */
    public function f(&$arg){
	    return $arg[1];	
	}
}
?>