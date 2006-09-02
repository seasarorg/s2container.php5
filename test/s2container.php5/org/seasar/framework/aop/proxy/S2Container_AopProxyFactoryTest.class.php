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
 * @package org.seasar.framework.aop.proxy
 * @author klove
 */
class S2Container_AopProxyFactoryTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testAopProxyFactory() {
        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW_S2Container_AopProxyFactory'),
                                            new S2Container_TraceInterceptor());
       
        $c = S2Container_AopProxyFactory::create(new WextendAW_S2Container_AopProxyFactory(),
                                                 new ReflectionClass('WextendAW_S2Container_AopProxyFactory'),
                                                 array($ad->getAspect()),
                                                 array(),
                                                 array());
        if($c instanceof IW_S2Container_AopProxyFactory){
            $this->assertTrue(true);
            $c->awm1();
        }else{
            $this->assertTrue(false);
        }              
              
        $c = S2Container_AopProxyFactory::create(null,
                                                 new ReflectionClass('IW_S2Container_AopProxyFactory'),
                                                 array($ad->getAspect()),
                                                 array(),
                                                 array());
        if($c instanceof IW_S2Container_AopProxyFactory){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }              
          
        $c = S2Container_AopProxyFactory::create(new WextendAW_S2Container_AopProxyFactory(),
                                                 null,
                                                 array($ad->getAspect()),
                                                 array(),
                                                 array());
        if($c instanceof IW_S2Container_AopProxyFactory){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }
    }

    function testNullTarget() {
        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW_S2Container_AopProxyFactory'),
                                            new S2Container_TraceInterceptor());

        try{
            $c = S2Container_AopProxyFactory::create(null,
                                                     null,
                                                     array($ad->getAspect()),
                                                     array(),
                                                     array());
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException',$e);
            print $e->getMessage() . "\n";
        }
    }
    
    function testNullAspect() {
        try{
            $c = S2Container_AopProxyFactory::create(new WextendAW_S2Container_AopProxyFactory(),
                                                     null,
                                                     array(),
                                                     array(),
                                                     array());
        }catch(Exception $e){
            $this->assertType('S2Container_EmptyRuntimeException',$e);
            print $e->getMessage() . "\n";
        }
    }
}

interface IO_S2Container_AopProxyFactory {
    function om1();
    function om2();
}

interface IW_S2Container_AopProxyFactory 
    extends IO_S2Container_AopProxyFactory {
    function wm1($arg1=null,IA &$a);
    function wm2();
}

abstract class AW_S2Container_AopProxyFactory implements IW_S2Container_AopProxyFactory{
    function om1(){
        print __METHOD__ . " called.\n";    
    }

    function om2(){
        print __METHOD__ . " called.\n";    
    }

    public function wm1($arg1,IA &$a){
        print __METHOD__ . " called.\n";    
    }

    function wm2(){
        print __METHOD__ . " called.\n";    
    }
    
    abstract function awm1();    
}

class WextendAW_S2Container_AopProxyFactory extends AW_S2Container_AopProxyFactory{
    function awm1(){
        print __METHOD__ . " called.\n";    
    }
}
?>
