<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id:$
/**
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
class S2Container_AopConcreteClassGeneratorTest extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    public function testInterface() {
        $targetClass = new ReflectionClass('A_S2Container_AopConcreteClassGeneratorTests');
        $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
        $className = $targetClass->getName() . S2Container_AopConcreteClassGenerator::CLASS_NAME_POSTFIX;
        $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
        $this->assertEquals($concreteClassName, $className);
        $obj = new $concreteClassName;
        $this->assertTrue(is_object($obj));
        $ref = new ReflectionClass($concreteClassName);
        $this->assertTrue($ref->hasMethod('aa_bb1_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb2_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb3_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb4_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb5_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb6_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb7_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb8_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb9_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb10_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb11_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb12_EnhancedByS2AOP'));
        $this->assertFalse($ref->hasMethod('aa_bb13_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb14_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeParentMethod_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP'));

        try {
            $targetClass = new ReflectionClass('D_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testClass() {
        $targetClass = new ReflectionClass('B_S2Container_AopConcreteClassGeneratorTests');
        $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
        $className = $targetClass->getName() . S2Container_AopConcreteClassGenerator::CLASS_NAME_POSTFIX;
        $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
        $this->assertEquals($concreteClassName, $className);
        $obj = new $concreteClassName;
        $this->assertTrue(is_object($obj));
        $ref = new ReflectionClass($concreteClassName);
        $this->assertTrue($ref->hasMethod('aa_bb1_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb2_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb3_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb4_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb5_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb6_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb7_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb8_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb9_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb10_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb11_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb12_EnhancedByS2AOP'));
        $this->assertFalse($ref->hasMethod('aa_bb13_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb14_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeParentMethod_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP'));

        try {
            $targetClass = new ReflectionClass('E_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testAbstractClass() {
        $targetClass = new ReflectionClass('C_S2Container_AopConcreteClassGeneratorTests');
        $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
        $className = $targetClass->getName() . S2Container_AopConcreteClassGenerator::CLASS_NAME_POSTFIX;
        $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
        $this->assertEquals($concreteClassName, $className);
        $obj = new $concreteClassName;
        $this->assertTrue(is_object($obj));
        $ref = new ReflectionClass($concreteClassName);
        $this->assertTrue($ref->hasMethod('aa_bb1_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb2_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb3_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb4_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb5_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb6_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb7_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb8_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb9_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb10_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb11_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb12_EnhancedByS2AOP'));
        $this->assertFalse($ref->hasMethod('aa_bb13_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('aa_bb14_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeParentMethod_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP'));

        try {
            $targetClass = new ReflectionClass('F_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testHasProperty() {
        try {
            $targetClass = new ReflectionClass('G_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
        try {
            $targetClass = new ReflectionClass('H_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testHasMethod() {
        try {
            $targetClass = new ReflectionClass('I_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }

        try {
            $targetClass = new ReflectionClass('J_S2Container_AopConcreteClassGeneratorTests');
            $applicableMethods = S2Container_AopConcreteClassFactory::getApplicableMethods($targetClass);
            $concreteClassName = S2Container_AopConcreteClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testGetMethodDefSrc() {
        $targetClass = new ReflectionClass('K_S2Container_AopConcreteClassGeneratorTests');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('a'));
        $this->assertEquals($methodDef,'public function a() {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('b'));
        $this->assertEquals($methodDef,'public function b($b) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('c'));
        $this->assertEquals($methodDef,'public function c(array $c) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('d'));
        $this->assertEquals($methodDef,'public function d(array &$d) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('e'));
        $this->assertEquals($methodDef,'public function e(Foo_S2Container_AopConcreteClassGeneratorTests $d) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('f'));
        $this->assertEquals($methodDef,'public function f($f = \'abc\') {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('g'));
        $this->assertEquals($methodDef,'public function g($g = array()) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('h'));
        $this->assertFalse($methodDef);
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('i'));
        $this->assertEquals($methodDef,'public function i(Foo_S2Container_AopConcreteClassGeneratorTests $d, $i = null) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('j'));
        $this->assertEquals($methodDef,'public function j($g = array(), $j = null) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('k'));
        $this->assertEquals($methodDef,'public function k($k = null, $g = \'abc\') {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('l'));
        $this->assertEquals($methodDef,'public function l($b, $l = null, $g = array()) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('m'));
        $this->assertEquals($methodDef,'public function m($m = true) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('n'));
        $this->assertEquals($methodDef,'public function n($n = false) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('o'));
        $this->assertEquals($methodDef,'public function o($o = 100) {');
        $methodDef = S2Container_AopConcreteClassGenerator::getMethodDefSrc($targetClass->getMethod('p'));
        $this->assertEquals($methodDef,'public function p($p = 0) {');
    }
}

interface IA_AopConcreteClassGeneratorTests{}
interface IB_AopConcreteClassGeneratorTests{}

interface A_S2Container_AopConcreteClassGeneratorTests {
    
    function aa_bb1();
    function aa_bb2();

    public function aa_bb3();
    
    public function aa_bb4(IA_AopConcreteClassGeneratorTests $a,
                           IB_AopConcreteClassGeneratorTests $b);

    public function aa_bb5(
                           IA_AopConcreteClassGeneratorTests $a,
                           IB_AopConcreteClassGeneratorTests &$b);

    public function aa_bb6( IA_AopConcreteClassGeneratorTests $a,

                           IB_AopConcreteClassGeneratorTests $b);

    public function aa_bb7( IA_AopConcreteClassGeneratorTests $a,

                           IB_AopConcreteClassGeneratorTests $b
                           );

    public function aa_bb8($a = '$b',$b = "test");

    public function aa_bb9($a = '
                                 $b',$b 
                                      = "test");

    public function aa_bb10(&$a = '$b',$b= "test");

    public function aa_bb11($a="abc",$b = "te\"st");
    public function aa_bb12($a="abc",$b = array());
    public function aa_bb14(S2Container $a);
}

class B_S2Container_AopConcreteClassGeneratorTests {
    
    function aa_bb1(){}
    function aa_bb2(){}

    public function aa_bb3(){}
    
    public function aa_bb4(IA_AopConcreteClassGeneratorTests $a,
                           IB_AopConcreteClassGeneratorTests $b){}

    public function aa_bb5(
                           IA_AopConcreteClassGeneratorTests $a,
                           IB_AopConcreteClassGeneratorTests &$b){}

    public function aa_bb6( IA_AopConcreteClassGeneratorTests $a,

                           IB_AopConcreteClassGeneratorTests $b){}

    public function aa_bb7( IA_AopConcreteClassGeneratorTests $a,

                           IB_AopConcreteClassGeneratorTests $b
                           ){}

    public function aa_bb8($a = '$b',$b = "test"){}

    public function aa_bb9($a = '
                                 $b',$b 
                                      = "test"){}

    public function aa_bb10(&$a = '$b',$b= "test"){}

    public function aa_bb11($a="abc",$b = "te\"st"){}
    public function aa_bb12($a="abc",$b = array()){}
    public function aa_bb13($a="abc",$b = array(1,
                                                2,
                                                3)){}
    public function aa_bb14(S2Container $a){}
}

abstract class C_S2Container_AopConcreteClassGeneratorTests {
    
    function aa_bb1(){}
    abstract function aa_bb2();

    public function aa_bb3(){}
    
    abstract public function aa_bb4(IA_AopConcreteClassGeneratorTests $a,
                           IB_AopConcreteClassGeneratorTests $b);

    abstract public function aa_bb5(
                           IA_AopConcreteClassGeneratorTests $a,
                           IB_AopConcreteClassGeneratorTests &$b);

    abstract public function aa_bb6( IA_AopConcreteClassGeneratorTests $a,

                           IB_AopConcreteClassGeneratorTests $b);

    public function aa_bb7( IA_AopConcreteClassGeneratorTests $a,

                           IB_AopConcreteClassGeneratorTests $b
                           ){}

    public function aa_bb8($a = '$b',$b = "test"){}

    public function aa_bb9($a = '
                                 $b',$b 
                                      = "test"){}

    public function aa_bb10(&$a = '$b',$b= "test"){}

    public function aa_bb11($a="abc",$b = "te\"st"){}
    public function aa_bb12($a="abc",$b = array()){}
    public function aa_bb13($a="abc",$b = array(1,
                                                2,
                                                3)){}
    abstract public function aa_bb14(S2Container $a)
    
    ;
}

interface D_S2Container_AopConcreteClassGeneratorTests {
    
    function aa_bb1();
    function aa_bb2();function aa_bb3();
}

class E_S2Container_AopConcreteClassGeneratorTests {
    
    function aa_bb1(){}
    function aa_bb2(){}  function aa_bb3(){}
}

abstract class F_S2Container_AopConcreteClassGeneratorTests {
    
    function aa_bb1(){}
    abstract function aa_bb2();function aa_bb3(){}
}

class G_S2Container_AopConcreteClassGeneratorTests {
    private $clazz_EnhancedByS2AOP = '';
    function aa_bb1(){}
}

abstract class H_S2Container_AopConcreteClassGeneratorTests {
    private $parameters_EnhancedByS2AOP = '';
    function aa_bb1(){}
}

abstract class I_S2Container_AopConcreteClassGeneratorTests {
    function __invokeMethodInvocationProceed_EnhancedByS2AOP(){}
}

class J_S2Container_AopConcreteClassGeneratorTests {
    function aa_bb1(){}
    function aa_bb1_EnhancedByS2AOP(){}
}

class K_S2Container_AopConcreteClassGeneratorTests {
    public function a(){}
    public function b($b){}
    public function c(array $c){}
    public function d(array &$d){}
    public function e(Foo_S2Container_AopConcreteClassGeneratorTests $d){}
    public function f($f = 'abc'){}
    public function g($g = array()){}
    public function h($h = array('a','b')){}
    public function i(Foo_S2Container_AopConcreteClassGeneratorTests $d, $i = null){}
    public function j($g = array(), $j = null){}
    public function k($k = null, $g = 'abc'){}
    public function l($b, $l = null, $g = array()){}
    public function m($m = true){}
    public function n($n = false){}
    public function o($o = 100){}
    public function p($p = 0){}
}

class Foo_S2Container_AopConcreteClassGeneratorTests {}
?>
