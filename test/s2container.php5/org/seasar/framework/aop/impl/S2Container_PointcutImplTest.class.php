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
 * @package org.seasar.framework.aop.impl
 * @author klove
 */
 class S2Container_PointcutImplTest extends PHPUnit_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print get_class($this) . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    public function testNullConstructArg() {
        try{
            $pc = new S2Container_PointcutImpl();       
        }catch(Exception $e){
            $this->assertType('S2Container_EmptyRuntimeException',$e);
        }

        try{
            $pc = new S2Container_PointcutImpl(array());       
        }catch(Exception $e){
            $this->assertType('S2Container_EmptyRuntimeException',$e);
        }
    }  

    function testIsApplied() {
        $pc = new S2Container_PointcutImpl(array('pm1','pm2','om1','om2'));
        $this->assertTrue($pc->isApplied('pm1'));       
        $this->assertTrue($pc->isApplied('pm2'));       
        $this->assertTrue($pc->isApplied('om1'));       
        $this->assertTrue($pc->isApplied('om2'));       

        $pc = new S2Container_PointcutImpl(new ReflectionClass('AW_S2Container_PointcutImpl'));
//        $this->assertTrue($pc->isApplied('wm1'));       
//        $this->assertTrue($pc->isApplied('wm2'));       
//        $this->assertTrue($pc->isApplied('om1'));       
//        $this->assertTrue($pc->isApplied('om2'));       
        $this->assertTrue($pc->isApplied('awm1'));       

        $pc = new S2Container_PointcutImpl(new ReflectionClass('C_S2Container_PointcutImpl'));
        $this->assertTrue($pc->isApplied('say') == false);       

        $pc = new S2Container_PointcutImpl(array('^a','b$'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('deb'));       
        $this->assertTrue($pc->isApplied('om') == false);       

        $pc = new S2Container_PointcutImpl(array('^(!?a)'));
        $this->assertTrue($pc->isApplied('abs'));       
        $this->assertFalse($pc->isApplied('deb'));       
        $this->assertFalse($pc->isApplied('om'));       

        $pc = new S2Container_PointcutImpl(array('(!?a)$'));
        $this->assertFalse($pc->isApplied('abs'));       
        $this->assertTrue($pc->isApplied('aba'));       
       
    }
}

interface IO_S2Container_PointcutImpl {
    function om1();
    function om2();
}

interface IW_S2Container_PointcutImpl 
    extends IO_S2Container_PointcutImpl {
    function wm1($arg1=null,IA &$a);
    function wm2();
}

abstract class AW_S2Container_PointcutImpl 
    implements IW_S2Container_PointcutImpl{

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

class C_S2Container_PointcutImpl {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}
?>
