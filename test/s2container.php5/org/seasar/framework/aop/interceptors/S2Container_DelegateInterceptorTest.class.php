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
 class S2Container_DelegateInterceptorTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testNoTarget() {
        $pointcut = new S2Container_PointcutImpl(array("ma"));
       
        $delegate = new S2Container_DelegateInterceptor();
        $delegate->addMethodNameMap('ma','mb');
        $aspect = new S2Container_AspectImpl($delegate, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA_S2Container_DelegateInterceptor(),
                                    'DelegateA_S2Container_DelegateInterceptor',
                                     array($aspect));
        try{
            $this->assertEquals($proxy->ma(),'mb called.');
        }catch(Exception $e){
            $this->assertType('S2Container_EmptyRuntimeException',$e);
        }
    }

    function testChangeMethodName() {
        $pointcut = new S2Container_PointcutImpl(array("ma"));
       
        $delegate = new S2Container_DelegateInterceptor(new DelegateB_S2Container_DelegateInterceptor());
        $delegate->addMethodNameMap('ma','mb');
        $aspect = new S2Container_AspectImpl($delegate, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA_S2Container_DelegateInterceptor(),'DelegateA_S2Container_DelegateInterceptor', array($aspect));
        $this->assertEquals($proxy->ma(),'mb called.');
    }

    function testDelegate() {       
        $pointcut = new S2Container_PointcutImpl(array("mc"));
       
        $delegate = new S2Container_DelegateInterceptor(new DelegateB_S2Container_DelegateInterceptor());
        $aspect = new S2Container_AspectImpl($delegate, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new DelegateA_S2Container_DelegateInterceptor(),'DelegateA_S2Container_DelegateInterceptor', array($aspect));
        $this->assertEquals($proxy->mc(),'Delegate B mc called.');
    }
}

interface IDelegateA_S2Container_DelegateInterceptor {
    function ma();
    function mc();
}
class DelegateA_S2Container_DelegateInterceptor implements IDelegateA_S2Container_DelegateInterceptor {
    function ma(){
        return "ma called.";
    }
    function mc(){
        return "mc called.";
    }
}


interface IDelegateB_S2Container_DelegateInterceptor {
    function mb();
    function mc();
}
class DelegateB_S2Container_DelegateInterceptor implements IDelegateB_S2Container_DelegateInterceptor {
    function mb(){
        return "mb called.";
    }
    function mc(){
        return "Delegate B mc called.";
    }
}
?>