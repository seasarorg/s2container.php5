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
 * @package org.seasar.framework.beans.impl
 * @author klove
 */
class S2Container_UuSetPropertyDescImplTest extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testAutoValueUUSet() {
        $container = new S2ContainerImpl();
        $container->register('M_S2Container_UuSetPropertyDescImpl','m');

        $ecd = $container->getComponentDef('m');
        $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('name','test-test');
        $ecd->addPropertyDef($pro);
          
        $m = $container->getComponent('m');
        $this->assertEquals($m->getName(),"test-test");
    }

    function testAutoValueUUSet2() {
        $container = new S2ContainerImpl();
        $container->register('M2_S2Container_UuSetPropertyDescImpl','m');

        $ecd = $container->getComponentDef('m');
        $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('val','test-test');
        $ecd->addPropertyDef($pro);
          
        $m = $container->getComponent('m');
        $this->assertEquals($m->getValue(),"test-test");
    }

    function testAutoValueUUSet3() {  
        $container = new S2ContainerImpl();
        $container->register('M3_S2Container_UuSetPropertyDescImpl','m');

        $ecd = $container->getComponentDef('m');
        $ecd->setAutoBindingMode(S2Container_ContainerConstants::AUTO_BINDING_CONSTRUCTOR);
        $pro = new S2Container_PropertyDefImpl('val','test-test');
        $ecd->addPropertyDef($pro);

        try{          
            $m = $container->getComponent('m');
        }catch(Exception $e){
            $this->assertType('S2Container_PropertyNotFoundRuntimeException',$e);
            print $e->getMessage() . "\n";
        }
    }
}

class M_S2Container_UuSetPropertyDescImpl {
    private $name;
    
    function __set($name,$value){
        print __METHOD__ . " called.\n";
       $this->$name = $value;    
    }
    
    function getName(){
        return $this->name;
    }
}

class M2_S2Container_UuSetPropertyDescImpl {
    private $val;
    
    function __set($name,$value){
        print __METHOD__ . " called.\n";
        $this->$name = $value;   
        print "property : $name, value : $value \n" ;
    }
    
    function getValue(){
        return $this->val;  
    }
}

class M3_S2Container_UuSetPropertyDescImpl {}
?>
