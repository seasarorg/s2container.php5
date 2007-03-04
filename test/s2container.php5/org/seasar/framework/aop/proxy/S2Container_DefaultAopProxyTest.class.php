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
class S2Container_DefaultAopProxyTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testNullTarget() {
        $pointcut = new S2Container_PointcutImpl(array("test"));
        $aspect = new S2Container_AspectImpl(new S2Container_TraceInterceptor(), $pointcut);
        $proxy = S2Container_AopProxyFactory::create(null,'C_S2Container_DefaultAopProxy', array($aspect));
        try{
            $proxy->say();
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException',$e);
            print $e->getMessage() . "\n";
        }
    }
}

class C_S2Container_DefaultAopProxy {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}
?>
