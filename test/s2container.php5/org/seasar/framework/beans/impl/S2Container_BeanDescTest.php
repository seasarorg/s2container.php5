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
 * @package org.seasar.framework.beans.impl
 */
/**
 * @file S2Container_BeanDescTest.php
 * @author 
 * @version $Id$
 */

require_once "TestCaseInitializer.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @package org.seasar.framework.beans.impl
 * @class S2Container_BeanDescTest
 */
class BeanDescTests extends PHPUnit2_Framework_TestCase {

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
     * testBeanDesc
     * @return 
     */
    public function testBeanDesc() {
    
       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);
       $this->assertType('S2Container_BeanDescImpl', $desc);

       $b = $desc->getBeanClass();
       $this->assertSame($a,$b);

       $constructor = $desc->getSuitableConstructor();
       $this->assertType('ReflectionMethod', $constructor);
       $this->assertEquals($constructor->getName(),'__construct');
   }
            
    /**
     * testGetPropertyDesc
     * @return 
     */
    public function testGetPropertyDesc() {

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $this->assertTrue($desc->hasPropertyDesc('val'));
       $this->assertTrue($desc->hasPropertyDesc('msg'));
       $this->assertTrue(!$desc->hasPropertyDesc('val3'));
       
       $propDesc = $desc->getPropertyDesc('val');
       $this->assertType('S2Container_PropertyDescImpl', $propDesc);
       $this->assertEquals($propDesc->getPropertyName(),'val');
       $this->assertTrue($propDesc->hasWriteMethod());
       $this->assertTrue($propDesc->hasReadMethod());
       $readMetnod = $propDesc->getReadMethod();
       $this->assertEquals($readMetnod->getName(),'getVal');
       $writeMetnod = $propDesc->getWriteMethod();
       $this->assertEquals($writeMetnod->getName(),'setVal');
       

       $propDesc = $desc->getPropertyDesc(0);
       $this->assertType('S2Container_PropertyDescImpl', $propDesc);
       $this->assertEquals($propDesc->getPropertyName(),'val');

       try{
           $propDesc = $desc->getPropertyDesc(2);
       }catch(Exception $e){
           $this->assertType('S2Container_PropertyNotFoundRuntimeException', $e);
       }

       try{
           $propDesc = $desc->getPropertyDesc('val2');
       }catch(Exception $e){
           $this->assertType('S2Container_PropertyNotFoundRuntimeException', $e);
       }
   }
            
    /**
     * testGetField
     * @return 
     */
    public function testGetField() {

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $this->assertTrue($desc->hasField('QUERY_1'));
       $this->assertTrue($desc->hasField('QUERY_2'));
       $this->assertTrue(!$desc->hasField('QUERY_3'));
       
       $field = $desc->getField('QUERY_1');
       $this->assertType('ReflectionProperty', $field);
       $this->assertEquals($field->getName(),'QUERY_1');
       
       try{
           $this->assertEquals($field->getValue(),'select * from talbe1;');
       }catch(Exception $e){
       	   $this->assertType('ReflectionException', $e);
       }

       try{
           $field = $desc->getField('QUERY_3');
       }catch(Exception $e){
       	   $this->assertType('S2Container_FieldNotFoundRuntimeException', $e);
       }
   }
            
    /**
     * testGetConstant
     * @return 
     */
    public function testGetConstant() {

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $this->assertTrue($desc->hasConstant('BEAN_A'));
       $this->assertTrue($desc->hasConstant('BEAN_B'));
       $this->assertTrue(!$desc->hasConstant('BEAN_C'));
       
       $value = $desc->getConstant('BEAN_A');
       $this->assertEquals($value,'TestBeanA');

       try{
           $field = $desc->getConstant('BEAN_C');
       }catch(Exception $e){
       	   $this->assertType('S2Container_ConstantNotFoundRuntimeException', $e);
       }
   }
            
    /**
     * testGetMethods
     * @return 
     */
    public function testGetMethods() {

       $a = new ReflectionClass('BeanDescSample');
       $desc = new S2Container_BeanDescImpl($a);

       $om = $desc->getMethods('om1');
   	   $this->assertType('ReflectionMethod', $om);

       try{
           $om = $desc->getMethods('omX');
       }catch(Exception $e){
       	   $this->assertType('S2Container_MethodNotFoundRuntimeException', $e);
       }
   }   
   
}

class BeanDescSample implements IO {

    const BEAN_A = "TestBeanA";
    const BEAN_B = "TestBeanB";

    private static $QUERY_1 = "select * from talbe1;";
    public static $QUERY_2 = "select * from table2;";
    
    private $msg;
    private $val;

    function __construct(){}
            
    /**
     * om1
     * @return 
     */
    public function om1(){}
            
    /**
     * om2
     * @return 
     */
    public function om2(){}
            
    /**
     * setVal
     * @param $val
     * @return 
     */
    public function setVal($val){
    	$this->val = $val;
    }
            
    /**
     * getVal
     * @return 
     */
    public function getVal(){
        return $this->val;	
    }
            
    /**
     * setMsg
     * @param $msg
     * @return 
     */
    public function setMsg($msg){
    	$this->msg = $msg;
    }
            
    /**
     * getMsg
     * @return 
     */
    public function getMsg(){
        return $this->msg;	
    }
}
?>
