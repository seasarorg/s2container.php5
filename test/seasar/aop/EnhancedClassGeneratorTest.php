<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
/**
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar::aop;
class EnhancedClassGeneratorTest extends ::PHPUnit_Framework_TestCase {

    public function testGenerateException() {
        $targetRef = new ReflectionClass('seasar::aop::L_EnhancedClassGenerator');
        $interceptor = new seasar::aop::interceptor::TraceInterceptor;
        $pointcut = new Pointcut($targetRef);
        $aspects = array(new Aspect($interceptor, $pointcut));
        try{
            $targetObj = S2AopFactory::create($targetRef, $aspects, array());
            $this->fail();
        } catch(seasar::aop::exception::EnhancedClassGenerationRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
            $this->fail();
        }

        $targetRef = new ReflectionClass('seasar::aop::M_EnhancedClassGenerator');
        $interceptor = new seasar::aop::interceptor::TraceInterceptor;
        $pointcut = new Pointcut($targetRef);
        $aspects = array(new Aspect($interceptor, $pointcut));
        try{
            $targetObj = S2AopFactory::create($targetRef, $aspects, array());
            $this->fail();
        } catch(seasar::aop::exception::EnhancedClassGenerationRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
            $this->fail();
        }
    }

    public function testInterface() {
        $targetClass = new ReflectionClass('seasar::aop::A_EnhancedClassGeneratorTest');
        $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
        $className = $targetClass->getName() . EnhancedClassGenerator::CLASS_NAME_POSTFIX;
        $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
        $this->assertEquals($concreteClassName, $className);
        $obj = new $concreteClassName;
        $this->assertTrue(is_object($obj));
        $ref = new ReflectionClass($concreteClassName);
        $this->assertTrue($ref->hasMethod('__invokeParentMethod_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP'));

        try {
            $targetClass = new ReflectionClass('seasar::aop::D_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testClass() {
        $targetClass = new ReflectionClass('seasar::aop::B_EnhancedClassGeneratorTest');
        $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
        $className = $targetClass->getName() . EnhancedClassGenerator::CLASS_NAME_POSTFIX;
        $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
        $this->assertEquals($concreteClassName, $className);
        $obj = new $concreteClassName;
        $this->assertTrue(is_object($obj));
        $ref = new ReflectionClass($concreteClassName);
        $this->assertTrue($ref->hasMethod('__invokeParentMethod_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP'));

        try {
            $targetClass = new ReflectionClass('seasar::aop::E_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testAbstractClass() {
        $targetClass = new ReflectionClass('seasar::aop::C_EnhancedClassGeneratorTest');
        $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
        $className = $targetClass->getName() . EnhancedClassGenerator::CLASS_NAME_POSTFIX;
        $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
        $this->assertEquals($concreteClassName, $className);
        $obj = new $concreteClassName;
        $this->assertTrue(is_object($obj));
        $ref = new ReflectionClass($concreteClassName);
        $this->assertTrue($ref->hasMethod('__invokeParentMethod_EnhancedByS2AOP'));
        $this->assertTrue($ref->hasMethod('__invokeMethodInvocationProceed_EnhancedByS2AOP'));

        try {
            $targetClass = new ReflectionClass('seasar::aop::F_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testHasProperty() {
        try {
            $targetClass = new ReflectionClass('seasar::aop::G_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
        try {
            $targetClass = new ReflectionClass('seasar::aop::H_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testHasMethod() {
        try {
            $targetClass = new ReflectionClass('seasar::aop::I_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }

        try {
            $targetClass = new ReflectionClass('seasar::aop::J_EnhancedClassGeneratorTest');
            $applicableMethods = S2AopFactory::getApplicableMethods($targetClass);
            $concreteClassName = EnhancedClassGenerator::generate($targetClass, $applicableMethods, array());
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage();
        }
    }

    public function testGetMethodDefSrc() {
        $targetClass = new ReflectionClass('seasar::aop::K_EnhancedClassGeneratorTest');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('a'));
        $this->assertEquals($methodDef,'public function a() {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('b'));
        $this->assertEquals($methodDef,'public function b($b) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('c'));
        $this->assertEquals($methodDef,'public function c(array $c) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('d'));
        $this->assertEquals($methodDef,'public function d(array &$d) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('e'));
        $this->assertEquals($methodDef,'public function e(seasar::aop::Foo_EnhancedClassGeneratorTest $d) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('f'));
        $this->assertEquals($methodDef,'public function f($f = \'abc\') {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('g'));
        $this->assertEquals($methodDef,'public function g($g = array()) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('h'));
        $this->assertFalse($methodDef);
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('i'));
        $this->assertEquals($methodDef,'public function i(seasar::aop::Foo_EnhancedClassGeneratorTest $d, $i = null) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('j'));
        $this->assertEquals($methodDef,'public function j($g = array(), $j = null) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('k'));
        $this->assertEquals($methodDef,'public function k($k = null, $g = \'abc\') {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('l'));
        $this->assertEquals($methodDef,'public function l($b, $l = null, $g = array()) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('m'));
        $this->assertEquals($methodDef,'public function m($m = true) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('n'));
        $this->assertEquals($methodDef,'public function n($n = false) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('o'));
        $this->assertEquals($methodDef,'public function o($o = 100) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('p'));
        $this->assertEquals($methodDef,'public function p($p = 0) {');
        $methodDef = EnhancedClassGenerator::getMethodDefSrc($targetClass->getMethod('q'));
        $this->assertEquals($methodDef,'public function q(::Sample_EnhancedClassGenerator $sample = null) {');
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

interface IA_EnhancedClassGeneratorTest{}
interface IB_EnhancedClassGeneratorTest{}

interface A_EnhancedClassGeneratorTest {
    
    function aa_bb1();
    function aa_bb2();

    public function aa_bb3();
    
    public function aa_bb4(IA_EnhancedClassGeneratorTest $a,
                           IB_EnhancedClassGeneratorTest $b);

    public function aa_bb5(
                           IA_EnhancedClassGeneratorTest $a,
                           IB_EnhancedClassGeneratorTest &$b);

    public function aa_bb6( IA_EnhancedClassGeneratorTest $a,

                           IB_EnhancedClassGeneratorTest $b);

    public function aa_bb7( IA_EnhancedClassGeneratorTest $a,

                           IB_EnhancedClassGeneratorTest $b
                           );

    public function aa_bb8($a = '$b',$b = "test");

    public function aa_bb9($a = '
                                 $b',$b 
                                      = "test");

    public function aa_bb10(&$a = '$b',$b= "test");

    public function aa_bb11($a="abc",$b = "te\"st");
    public function aa_bb12($a="abc",$b = array());
    public function aa_bb14(seasar::container::S2Container $a);
}

class B_EnhancedClassGeneratorTest {
    
    function aa_bb1(){}
    function aa_bb2(){}

    public function aa_bb3(){}
    
    public function aa_bb4(IA_EnhancedClassGeneratorTest $a,
                           IB_EnhancedClassGeneratorTest $b){}

    public function aa_bb5(
                           IA_EnhancedClassGeneratorTest $a,
                           IB_EnhancedClassGeneratorTest &$b){}

    public function aa_bb6( IA_EnhancedClassGeneratorTest $a,

                           IB_EnhancedClassGeneratorTest $b){}

    public function aa_bb7( IA_EnhancedClassGeneratorTest $a,

                           IB_EnhancedClassGeneratorTest $b
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
    public function aa_bb14(seasar::container::S2Container $a){}
}

abstract class C_EnhancedClassGeneratorTest {
    
    function aa_bb1(){}
    abstract function aa_bb2();

    public function aa_bb3(){}
    
    abstract public function aa_bb4(IA_EnhancedClassGeneratorTest $a,
                           IB_EnhancedClassGeneratorTest $b);

    abstract public function aa_bb5(
                           IA_EnhancedClassGeneratorTest $a,
                           IB_EnhancedClassGeneratorTest &$b);

    abstract public function aa_bb6( IA_EnhancedClassGeneratorTest $a,

                           IB_EnhancedClassGeneratorTest $b);

    public function aa_bb7( IA_EnhancedClassGeneratorTest $a,

                           IB_EnhancedClassGeneratorTest $b
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
    abstract public function aa_bb14(seasar::container::S2Container $a)
    
    ;
}

interface D_EnhancedClassGeneratorTest {
    
    function aa_bb1();
    function aa_bb2();function aa_bb3();
}

class E_EnhancedClassGeneratorTest {
    
    function aa_bb1(){}
    function aa_bb2(){}  function aa_bb3(){}
}

abstract class F_EnhancedClassGeneratorTest {
    
    function aa_bb1(){}
    abstract function aa_bb2();function aa_bb3(){}
}

class G_EnhancedClassGeneratorTest {
    private $clazz_EnhancedByS2AOP = '';
    function aa_bb1(){}
}

abstract class H_EnhancedClassGeneratorTest {
    private $parameters_EnhancedByS2AOP = '';
    function aa_bb1(){}
}

abstract class I_EnhancedClassGeneratorTest {
    function __invokeMethodInvocationProceed_EnhancedByS2AOP(){}
}

class J_EnhancedClassGeneratorTest {
    function aa_bb1(){}
    function aa_bb1_EnhancedByS2AOP(){}
}

require_once(dirname(__FILE__) . '/Sample_EnhancedClassGenerator.php');
class K_EnhancedClassGeneratorTest {
    public function a(){}
    public function b($b){}
    public function c(array $c){}
    public function d(array &$d){}
    public function e(Foo_EnhancedClassGeneratorTest $d){}
    public function f($f = 'abc'){}
    public function g($g = array()){}
    public function h($h = array('a','b')){}
    public function i(Foo_EnhancedClassGeneratorTest $d, $i = null){}
    public function j($g = array(), $j = null){}
    public function k($k = null, $g = 'abc'){}
    public function l($b, $l = null, $g = array()){}
    public function m($m = true){}
    public function n($n = false){}
    public function o($o = 100){}
    public function p($p = 0){}
    public function q(::Sample_EnhancedClassGenerator $sample = null){}
}

class Foo_EnhancedClassGeneratorTest {}

class L_EnhancedClassGenerator {
    private $class_EnhancedByS2AOP = null;
}

class M_EnhancedClassGenerator {
    public function service_EnhancedByS2AOP(){}
    public function service(){}
}

