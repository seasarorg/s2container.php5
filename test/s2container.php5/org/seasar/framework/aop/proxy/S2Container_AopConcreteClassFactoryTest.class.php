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
class S2Container_AopConcreteClassFactoryTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testAopConcreteClassFactory() {
        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW_S2Container_AopConcreteClassFactory'),
                                            new S2Container_TraceInterceptor());
       
        $c = S2Container_AopConcreteClassFactory::create(array(),
                                                 new ReflectionClass('WextendAW_S2Container_AopConcreteClassFactory'),
                                                 array($ad->getAspect()),
                                                 array(),
                                                 array());
        $this->assertTrue($c instanceof IW_S2Container_AopConcreteClassFactory);
        $c->awm1();

        $c = S2Container_AopConcreteClassFactory::create(array(),
                                                 new ReflectionClass('IW_S2Container_AopConcreteClassFactory'),
                                                 array($ad->getAspect()),
                                                 array(),
                                                 array());
        $this->assertTrue($c instanceof IW_S2Container_AopConcreteClassFactory);
    }

    function testNullAspect() {
        $c = S2Container_AopConcreteClassFactory::create(array(),
                                                 new ReflectionClass('WextendAW_S2Container_AopConcreteClassFactory'),
                                                 array(),
                                                 array(),
                                                 array());
        $this->assertType('WextendAW_S2Container_AopConcreteClassFactory',$c);
    }

    function testFinalClassAspect() {
        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('WextendAW_S2Container_AopConcreteClassFactory'),
                                            new S2Container_TraceInterceptor());
        try{
            $c = S2Container_AopConcreteClassFactory::create(array(),
                                                 new ReflectionClass('A_S2Container_AopConcreteClassFactory'),
                                                 array($ad->getAspect()),
                                                 array(),
                                                 array());
            $this->fail();
        }catch(Exception $e){
            print $e->getMessage() . "\n";
        }
    }
}

interface IO_S2Container_AopConcreteClassFactory {
    function om1();
    function om2();
}

interface IW_S2Container_AopConcreteClassFactory 
    extends IO_S2Container_AopConcreteClassFactory {
    function wm1($arg1=null,IA_S2Container_AopConcreteClassFactory &$a);
    function wm2();
}

abstract class AW_S2Container_AopConcreteClassFactory implements IW_S2Container_AopConcreteClassFactory{
    function om1(){
        print __METHOD__ . " called.\n";    
    }

    function om2(){
        print __METHOD__ . " called.\n";    
    }

    public function wm1($arg1,IA_S2Container_AopConcreteClassFactory &$a){
        print __METHOD__ . " called.\n";    
    }

    function wm2(){
        print __METHOD__ . " called.\n";    
    }
    
    abstract function awm1();    
}

class WextendAW_S2Container_AopConcreteClassFactory extends AW_S2Container_AopConcreteClassFactory{
    function awm1(){
        print __METHOD__ . " called.\n";    
    }
}

final class A_S2Container_AopConcreteClassFactory {}
interface IA_S2Container_AopConcreteClassFactory{}
?>
