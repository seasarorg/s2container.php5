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
class S2Container_TraceInterceptorTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testTraceInterceptor() {
        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new Date_S2Container_TraceInterceptor(),
                   'Date_S2Container_TraceInterceptor',
                   array($aspect));
        $this->assertEquals($proxy->getTime(),'12:00:30');
    }

    function testArgs() {
        $pointcut = new S2Container_PointcutImpl(array("culc2"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new I_S2Container_TraceInterceptor(),
                  'I_S2Container_TraceInterceptor', 
                  array($aspect));
        $proxy->culc2(4,8);
        $this->assertEquals($proxy->getResult(),12);
    }

    function testTraceInterceptorContaienr() {
        $container = new S2ContainerImpl();
        $container->register('Date_S2Container_TraceInterceptor','d');
        $cd = $container->getComponentDef('d');

        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $aspectDef = new S2Container_AspectDefImpl(new S2Container_TraceInterceptor(), $pointcut);
        $cd->addAspectDef($aspectDef);
        $d = $container->getComponent('d');

        $this->assertEquals($d->getTime(),'12:00:30');
    }

    function testObject() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_TraceInterceptor','a');
        $cd = $container->getComponentDef('a');

        $pointcut = new S2Container_PointcutImpl(array("testArgObj"));
        $aspectDef = new S2Container_AspectDefImpl(
                     new S2Container_TraceInterceptor(),
                     $pointcut);
        $cd->addAspectDef($aspectDef);
        $a = $container->getComponent('a');
        $b = new B_S2Container_TraceInterceptor();
        $this->assertEquals($a->testArgObj($b),$b);
    }

    function testObjectArgs() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_TraceInterceptor','a');
        $cd = $container->getComponentDef('a');

        $pointcut = new S2Container_PointcutImpl(array("testArgObj2"));
        $aspectDef = new S2Container_AspectDefImpl(
                     new S2Container_TraceInterceptor(),
                     $pointcut);
        $cd->addAspectDef($aspectDef);
        $a = $container->getComponent('a');
        $b = new B_S2Container_TraceInterceptor();
        $this->assertEquals($a->testArgObj2($b,2006,'seasar'),$b);
    }

    function testArray() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_TraceInterceptor','a');
        $cd = $container->getComponentDef('a');

        $pointcut = new S2Container_PointcutImpl(array("testArgArray"));
        $aspectDef = new S2Container_AspectDefImpl(
                     new S2Container_TraceInterceptor(),
                     $pointcut);
        $cd->addAspectDef($aspectDef);
        $a = $container->getComponent('a');
        $b = new B_S2Container_TraceInterceptor();
        $this->assertTrue(is_array($a->testArgArray(array($b,2006,'seasar'))));
    }

    function testArrayArgs() {
        $container = new S2ContainerImpl();
        $container->register('A_S2Container_TraceInterceptor','a');
        $cd = $container->getComponentDef('a');

        $pointcut = new S2Container_PointcutImpl(array("testArgArray2"));
        $aspectDef = new S2Container_AspectDefImpl(
                     new S2Container_TraceInterceptor(),
                     $pointcut);
        $cd->addAspectDef($aspectDef);
        $a = $container->getComponent('a');
        $b = new B_S2Container_TraceInterceptor();
        $this->assertTrue(is_array($a->testArgArray2(array($b,2006,'seasar'),2006,'seasar',$b)));
    }
}

class Date_S2Container_TraceInterceptor {
    function getTime(){
        return '12:00:30';
    }

    function getDay(){
        return '25';
    }
}

interface IG_S2Container_TraceInterceptor {}

class D_S2Container_TraceInterceptor implements IG_S2Container_TraceInterceptor{}

class I_S2Container_TraceInterceptor {

    private $result = -1;
    
    function culc(){
        $this->result = 1+1;
    }

    function culc2($a,$b){
        return $this->result = $a+$b;
    }

    function culc3(IG_S2Container_TraceInterceptor $d){
        if($d instanceof D_S2Container_TraceInterceptor){
            $this->result = 4;
        }else{return -1;}
    }
    
    function getResult(){
        return $this->result;
    }
}

class A_S2Container_TraceInterceptor {
    public function testArgObj($obj){
        return $obj;        
    }   

    public function testArgObj2($obj,$val,$str){
        return $obj;        
    }   

    public function testArgArray($arr){
        return $arr;        
    }   

    public function testArgArray2($arr,$val,$str,$obj){
        return $arr;        
    }   
}

class B_S2Container_TraceInterceptor {}
?>
