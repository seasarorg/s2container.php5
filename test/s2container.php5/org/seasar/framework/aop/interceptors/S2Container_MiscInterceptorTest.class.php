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

    function testUuCallMethod() {
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(),new S2Container_PointcutImpl(array("getMessage")));
        $proxy = S2Container_AopProxyFactory::create(new X_S2Container_MiscInterceptor(),
                           'X_S2Container_MiscInterceptor',
                           array($aspect));
        $this->assertEquals($proxy->getMessage(),'hello');
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
        }catch(Exception $e){

            // no exception occure. final class permitted.
            if($e instanceof S2Container_S2RuntimeException ){
                $this->assertTrue(true);
                print($e->getMessage()."\n");
            }else{
                $this->assertTrue(false);
            }           
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
    function __call($name,$args){}
    
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
?>
