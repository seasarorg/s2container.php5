<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class S2Container_MiscInterceptorTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    /**
     * aspect enabled, even if target implements __call method.
     */
    function testUuCallMethod1() {
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(),
                  new S2Container_PointcutImpl(array("getMessage")));
        $proxy = S2Container_AopProxyFactory::create(new X_S2Container_MiscInterceptor(),
                           'X_S2Container_MiscInterceptor',
                           array($aspect));
        $this->assertEquals($proxy->getMessage(),'hello');
        $this->assertEquals($proxy->__call('getMessage',array()),'hello');
    }
    
    /**
     * client       ->         proxy        ->     target
     *   p->__call('hoge',..    __call() 
     *                             t->hoge()         $this->__call()
     */
    function testUuCallMethod2(){
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(),
                  new S2Container_PointcutImpl(array("getMessage")));
        $proxy = S2Container_AopProxyFactory::create(new X_S2Container_MiscInterceptor(),
                           'X_S2Container_MiscInterceptor',
                           array($aspect));
        $result = $proxy->__call('hoge',array('2006','seasar'));
        $this->assertEquals($result[0],'hoge');
        $this->assertEquals($result[1][0],'2006');
        $this->assertEquals($result[1][1],'seasar');
    }
    
    /**
     * client       ->   proxy     ->   target
     *   p->hoge()         t->hoge()      $this->__call()
     */
    function testUuCallMethod3(){    
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(),
                  new S2Container_PointcutImpl(array("getMessage")));
        $proxy = S2Container_AopProxyFactory::create(new X_S2Container_MiscInterceptor(),
                           'X_S2Container_MiscInterceptor',
                           array($aspect));
        $result = $proxy->hoge('2006','seasar');
        $this->assertEquals($result[0],'hoge');
        $this->assertEquals($result[1][0],'2006');
        $this->assertEquals($result[1][1],'seasar');
    }
    
    /**
     * client       ->         proxy        ->     target
     *   p->__call('__call',..    __call() 
     *                              t->__call()         $this->__call()
     */
    function testUuCallMethod4(){    
        $aspect = new S2Container_AspectImpl(new TestInterceptor_S2Container_MiscInterceptor(),
                  new S2Container_PointcutImpl(array("__call")));
        $proxy = S2Container_AopProxyFactory::create(new X_S2Container_MiscInterceptor(),
                           'X_S2Container_MiscInterceptor',
                           array($aspect));
        $result = $proxy->__call('__call',array('hoge',array('2006','seasar')));
        $this->assertEquals($result[0],'hoge');
        $this->assertEquals($result[1][0],'2006');
        $this->assertEquals($result[1][1],'seasar');
    }

    function testUuCallMethod5(){    
        $aspect = new S2Container_AspectImpl(new TestInterceptor_S2Container_MiscInterceptor(),
                  new S2Container_PointcutImpl(array("foo")));
        $proxy = S2Container_AopProxyFactory::create(new B_S2Container_MiscInterceptor(),
                           'B_S2Container_MiscInterceptor',
                           array($aspect));
        try {
            $result = $proxy->bar();
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertTrue(true);
            print "{$e->getMessage()} \n";
        }
    }

    function testNoMethodInterface() {
        $pointcut = new S2Container_PointcutImpl('IA_S2Container_MiscInterceptor');
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'IA_S2Container_MiscInterceptor', array($aspect));
        $this->assertNotNull($proxy);
    } 

    function testFinalClassAspect() {
        $pointcut = new S2Container_PointcutImpl('Y_S2Container_MiscInterceptor');
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        try{
            $proxy = S2Container_AopProxyFactory::create(null,'Y_S2Container_MiscInterceptor', array($aspect));
            $this->assertTrue(true);
        }catch(Exception $e){
            // no exception occure. final class permitted.
            $this->assertTrue(false);
        }
    } 

    function testStaticMethodAspect() {
        $pointcut = new S2Container_PointcutImpl(array('z1','z2'));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new Z_S2Container_MiscInterceptor(),
                    'Z_S2Container_MiscInterceptor',
                    array($aspect));
        $this->assertNotNull($proxy);
        $this->assertTrue($proxy->z2());
    } 

    function testInvokeInterfaceMethod() {
        $pointcut = new S2Container_PointcutImpl(array('om2'));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,
                 'IO_S2Container_MiscInterceptor',
                 array($aspect));
        $this->assertNotNull($proxy);
        try{
            $proxy->om1();  
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException',$e);
            print $e->getMessage() . "\n";
        }
    } 
}

class X_S2Container_MiscInterceptor {
    function __call($name,$args){
        return array($name,$args); 
    }
    
    function getMessage(){
        return "hello"; 
    }
}

interface IA_S2Container_MiscInterceptor {}


final class Y_S2Container_MiscInterceptor {
    function y1(){}
}

interface IZ_S2Container_MiscInterceptor {
    static function z1();
    function z2();
}
class Z_S2Container_MiscInterceptor 
    implements IZ_S2Container_MiscInterceptor{
    static function z1(){}
    function z2(){ return true;}
}

interface IO_S2Container_MiscInterceptor {
    function om1();
    function om2();
}

class TestInterceptor_S2Container_MiscInterceptor 
    extends S2Container_AbstractInterceptor
{
    public function invoke(S2Container_MethodInvocation $invocation){
        print __METHOD__ . " called. \n";
        return $invocation->proceed();
    }
}

class B_S2Container_MiscInterceptor {
    public function foo(){
        return 'foo';   
    }   
}
?>
