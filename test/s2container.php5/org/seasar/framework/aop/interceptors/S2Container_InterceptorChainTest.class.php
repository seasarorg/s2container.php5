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
class S2Container_InterceptorChainTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testInterceptorChain() {
        $pointcut = new S2Container_PointcutImpl(array("pm1"));
        $chain = new S2Container_InterceptorChain();
        $chain->add(new S2Container_TraceInterceptor());
        $chain->add(new S2Container_MockInterceptor('pm1','mock value.'));
        $aspect = new S2Container_AspectImpl($chain, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new P_S2Container_InterceptorChain(),
                                 'P_S2Container_InterceptorChain',
                                 array($aspect));
        $this->assertEquals($proxy->pm1(),'mock value.');
    } 
}

interface IO_S2Container_InterceptorChain {
    function om1();
    function om2();
}
interface IP_S2Container_InterceptorChain {
    function pm1();
    function pm2();
}
class O_S2Container_InterceptorChain 
    implements IO_S2Container_InterceptorChain {
    function om1() {}
    function om2() {}
    function om3() {}   
}
class P_S2Container_InterceptorChain 
    extends O_S2Container_InterceptorChain 
    implements IP_S2Container_InterceptorChain{
    function pm1(){}
    function pm2(){}
    function pm3(){}
}
?>
