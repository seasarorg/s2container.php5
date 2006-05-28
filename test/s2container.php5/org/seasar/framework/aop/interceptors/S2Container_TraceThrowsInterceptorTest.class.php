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
class S2Container_TraceThrowsInterceptorTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testTraceThrowsInterceptor() {
        $pointcut = new S2Container_PointcutImpl(array("throwE"));
       
        $tt = new S2Container_TraceThrowsInterceptor();
        $aspect = new S2Container_AspectImpl($tt, $pointcut);
        $proxy = S2Container_AopProxyFactory::create(new Q_S2Container_TraceThrowsInterceptor(),
                             'Q_S2Container_TraceThrowsInterceptor',
                             array($aspect));
        try{
            $proxy->throwE();
        }catch(Exception $e){
            $this->assertType('S2Container_UnsupportedOperationException',$e);
        }
    }  
}

class Q_S2Container_TraceThrowsInterceptor {
    function throwE(){
        throw new S2Container_UnsupportedOperationException("throwE");
    }
   
    function doNone(){
        print "void method called.";    
        throw new S2Container_UnsupportedOperationException("throwE");
    }    
}
?>
