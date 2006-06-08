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
 * @package org.seasar.framework.container.util
 * @author klove
 */
class S2Container_ConstructerUtilTest
    extends PHPUnit2_Framework_TestCase {

    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testInstance() {
        $a = S2Container_ConstructorUtil::newInstance(new ReflectionClass('A_S2Container_ConstructerUtil'),
                                         array());
        $this->assertType('A_S2Container_ConstructerUtil',$a);

        $a = S2Container_ConstructorUtil::newInstance(new ReflectionClass('A_S2Container_ConstructerUtil'),
                                         null);
        $this->assertType('A_S2Container_ConstructerUtil',$a);
    }

    function testInstanceWithArgs() {
        $c = S2Container_ConstructorUtil::newInstance(new ReflectionClass('C_S2Container_ConstructerUtil'),
                                         array('hoge'));
        $this->assertType('C_S2Container_ConstructerUtil',$c);
    }

    function testIllegalRelfection() {
        try{
            $c = S2Container_ConstructorUtil::newInstance('C_S2Container_ConstructerUtil',array('hoge'));
        }catch(Exception $e){
            $this->assertType('S2Container_IllegalArgumentException',$e);
            print "{$e->getMessage()}\n";
        }
    }
}

interface IA_S2Container_ConstructerUtil{}
class A_S2Container_ConstructerUtil 
    implements IA_S2Container_ConstructerUtil {}
    
class C_S2Container_ConstructerUtil {
    private $name;
    function __construct($name) {
        $this->name =$name;
    }
    
    public function say(){
        return $this->name;    
    }
}
?>
