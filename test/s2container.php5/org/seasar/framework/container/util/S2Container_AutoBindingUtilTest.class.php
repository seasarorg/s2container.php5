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
 * @package org.seasar.framework.container.util
 * @author klove
 */
class S2Container_AutoBindingUtilTest
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

    function testMode() {
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('auto'));
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('AUTO'));
        $this->assertTrue(S2Container_AutoBindingUtil::isAuto('auTo'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isAuto('au'));

        $this->assertTrue(S2Container_AutoBindingUtil::isConstructor('constructor'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isConstructor('con'));

        $this->assertTrue(S2Container_AutoBindingUtil::isProperty('property'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isProperty('prop'));

        $this->assertTrue(S2Container_AutoBindingUtil::isNone('none'));
        $this->assertTrue(!S2Container_AutoBindingUtil::isNone('no'));
    }

    function testIsSuitable() {
        $this->assertTrue(S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('IA_S2Container_AutoBindingUtil')));
        if (defined('S2CONTAINER_PHP5_PERMIT_CLASS_INJECTION') and
            S2CONTAINER_PHP5_PERMIT_CLASS_INJECTION === true){
            $this->assertTrue(S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('A_S2Container_AutoBindingUtil')));
        } else {
            $this->assertFalse(S2Container_AutoBindingUtil::isSuitable(new ReflectionClass('A_S2Container_AutoBindingUtil')));
        }
        $res = array(new ReflectionClass('IA_S2Container_AutoBindingUtil'),
                      new ReflectionClass('IB_S2Container_AutoBindingUtil'));
        $this->assertTrue(S2Container_AutoBindingUtil::isSuitable($res));

        $res = array(new ReflectionClass('A_S2Container_AutoBindingUtil'),
                      new ReflectionClass('IB_S2Container_AutoBindingUtil'));

        if (defined('S2CONTAINER_PHP5_PERMIT_CLASS_INJECTION') and
            S2CONTAINER_PHP5_PERMIT_CLASS_INJECTION === true){
            $this->assertTrue(S2Container_AutoBindingUtil::isSuitable($res));
        } else {
            $this->assertFalse(S2Container_AutoBindingUtil::isSuitable($res));
        }
    }
}

interface IA_S2Container_AutoBindingUtil{}

class A_S2Container_AutoBindingUtil implements IA_S2Container_AutoBindingUtil{
    function __construct(){}   
}
interface IB_S2Container_AutoBindingUtil{}
class B_S2Container_AutoBindingUtil
    extends A_S2Container_AutoBindingUtil
    implements IB_S2Container_AutoBindingUtil{

    function __construct() {
        parent::__construct();
    }
}

?>
