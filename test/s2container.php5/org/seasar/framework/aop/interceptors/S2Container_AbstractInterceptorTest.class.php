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
 class S2Container_AbstractInterceptorTest extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testCreateProxy() {
        $interceptor = new S2Container_TraceInterceptor();
        $proxy = $interceptor->createProxy(new ReflectionClass('I_S2Container_AbstractInterceptor'));
        $proxy->target_ = new I_S2Container_AbstractInterceptor();
        $proxy->culc();
        $this->assertEquals($proxy->getResult(),2);
    }

    function testGetTargetClass() {
        $interceptor = new TestInterceptor_S2Container_AbstractInterceptor();
        $proxy = $interceptor->createProxy(new ReflectionClass('I_S2Container_AbstractInterceptor'));
        $proxy->culc();
        $this->assertType('ReflectionClass',$interceptor->getClazz());
    }

    function testGetComponentDef() {
        $pointcut = new S2Container_PointcutImpl(array("getTime"));
        $interceptor = new TestInterceptor_S2Container_AbstractInterceptor();
        $aspect = new S2Container_AspectImpl($interceptor, $pointcut);
        $params[S2Container_ContainerConstants::COMPONENT_DEF_NAME] = new S2Container_ComponentDefImpl('Date_S2Container_AbstractInterceptor','date');
        $proxy = S2Container_AopProxyFactory::create(null,'Date_S2Container_AbstractInterceptor', array($aspect), array(), $params);
        $proxy->target_ = new Date_S2Container_AbstractInterceptor();
        $proxy->getTime();
        $cd = $interceptor->getCd();
        
        $this->assertType('S2Container_ComponentDefImpl',$cd);
    }
}

class Date_S2Container_AbstractInterceptor {
    
    function getTime(){
        return '12:00:30';
    }

    function getDay(){
        return '25';
    }
}

interface IG_S2Container_AbstractInterceptor{}

class I_S2Container_AbstractInterceptor {

    private $result = -1;
    
    function I() {
    }
    
    function culc(){
        $this->result = 1+1;
    }

    function culc2($a,$b){

        $this->result = $a+$b;
    }

    function culc3(IG_S2Container_AbstractInterceptor $d){
        if($d instanceof D){
            $this->result = 4;
        }else{return -1;}
    }
    
    function getResult(){
        return $this->result;
    }
}

class TestInterceptor_S2Container_AbstractInterceptor extends S2Container_AbstractInterceptor {
    private $clazz;
    private $cd;
    
    public function invoke(S2Container_MethodInvocation $invocation){
        $this->clazz = $this->getTargetClass($invocation);
        $this->cd = $this->getComponentDef($invocation);
    }
    
    public function getClazz(){
        return $this->clazz;    
    }

    public function getCd(){
        return $this->cd;   
    }
}
?>
