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
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class S2Container_MockInterceptorTest extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }
 
    function testMockInterceptor() {
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
       
        $mock = new S2Container_MockInterceptor('pm1','mock value.');
        $aspect = new S2Container_AspectImpl($mock, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'IP_S2Container_MockInterceptor', array($aspect));
        $this->assertEquals($proxy->pm1(),'mock value.');
    }

    function testTraceAndMock() {
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
       
        $taspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $mock = new S2Container_MockInterceptor('pm1','mock value.');
        $aspect = new S2Container_AspectImpl($mock, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new P_S2Container_MockInterceptor(),
                      'P_S2Container_MockInterceptor',
                      array($taspect,$aspect));
        $this->assertEquals($proxy->pm1(),'mock value.');
    }

    function testNullReturnValue() {
        $pointcut = new S2Container_PointcutImpl('IO_S2Container_MockInterceptor');
        $mock = new S2Container_MockInterceptor();
        $mock->setReturnValue(null,'null value.');
        $aspect = new S2Container_AspectImpl($mock,$pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,
                 'IO_S2Container_MockInterceptor',
                 array($aspect));
        $this->assertEquals($proxy->om1(),'null value.');
    }

    function testNullThrowable() {
        $pointcut = new S2Container_PointcutImpl('IO_S2Container_MockInterceptor');
        $mock = new S2Container_MockInterceptor();
        $mock->setThrowable(null,new Exception('throw test'));
        $aspect = new S2Container_AspectImpl($mock,$pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,
                 'IO_S2Container_MockInterceptor',
                 array($aspect));
        try{
            $proxy->om1();
            $this->assertTrue(false);
        } catch (Exception $e){
            $this->assertEquals($e->getMessage(),'throw test');
        }
    }

    function testThrowable() {
        $pointcut = new S2Container_PointcutImpl('IO_S2Container_MockInterceptor');
        $mock = new S2Container_MockInterceptor();
        $mock->setThrowable('om1',new Exception('throw test'));
        $aspect = new S2Container_AspectImpl($mock,$pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,
                 'IO_S2Container_MockInterceptor',
                 array($aspect));
        try{
            $proxy->om1();
            $this->assertTrue(false);
        } catch (Exception $e){
            $this->assertEquals($e->getMessage(),'throw test');
        }
    }

    function testDoNothing() {
        $pointcut = new S2Container_PointcutImpl('IO_S2Container_MockInterceptor');
        $mock = new S2Container_MockInterceptor();
        $aspect = new S2Container_AspectImpl($mock,$pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,
                 'IO_S2Container_MockInterceptor',
                 array($aspect));
        $this->assertEquals($proxy->om1(),null);
    }
}

interface IO_S2Container_MockInterceptor {
    function om1();
    function om2();
}
interface IP_S2Container_MockInterceptor {
    function pm1();
    function pm2();
}
class O_S2Container_MockInterceptor 
    implements IO_S2Container_MockInterceptor {
    function om1() {}
    function om2() {}
    function om3() {}   
}
class P_S2Container_MockInterceptor 
    extends O_S2Container_MockInterceptor 
    implements IP_S2Container_MockInterceptor{
    function pm1(){}
    function pm2(){}
    function pm3(){}
}
?>
