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
class S2Container_InstanceModeUtilTest
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

    function testIsOuter() {
        $this->assertTrue(S2Container_InstanceModeUtil::isOuter('outer'));
        $this->assertTrue(S2Container_InstanceModeUtil::isOuter('oUter'));
        $this->assertFalse(S2Container_InstanceModeUtil::isOuter('oouter'));
    }

    function testIsSingleton() {
        $this->assertTrue(S2Container_InstanceModeUtil::isSingleton('singleton'));
        $this->assertTrue(S2Container_InstanceModeUtil::isSingleton('SingleTon'));
        $this->assertFalse(S2Container_InstanceModeUtil::isSingleton('single'));
    }

    function testIsPrototype() {
        $this->assertTrue(S2Container_InstanceModeUtil::isPrototype('prototype'));
        $this->assertTrue(S2Container_InstanceModeUtil::isPrototype('Prototype'));
        $this->assertFalse(S2Container_InstanceModeUtil::isPrototype('pro'));
    }    

    function testIsRequest() {
        $this->assertTrue(S2Container_InstanceModeUtil::isRequest('request'));
        $this->assertTrue(S2Container_InstanceModeUtil::isRequest('Request'));
        $this->assertFalse(S2Container_InstanceModeUtil::isRequest('req'));
    }    

    function testIsSession() {
        $this->assertTrue(S2Container_InstanceModeUtil::isSession('session'));
        $this->assertTrue(S2Container_InstanceModeUtil::isSession('Session'));
        $this->assertFalse(S2Container_InstanceModeUtil::isSession('ses'));
    }    
}
?>
