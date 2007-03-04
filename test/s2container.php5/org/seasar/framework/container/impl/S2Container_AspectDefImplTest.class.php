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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2Container_AspectDefImplTest
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

    function testGetAspect() {
        $ad = new S2Container_AspectDefImpl(new S2Container_PointcutImpl('A_S2Container_AspectDefImpl'),
                                new S2Container_TraceInterceptor());
        $aspect = $ad->getValue();
        $this->assertType('S2Container_TraceInterceptor',$aspect);

        $aspect = $ad->getAspect();
        $this->assertType('S2Container_AspectImpl',$aspect);
    } 
}

interface IA_S2Container_AspectDefImpl {}
class A_S2Container_AspectDefImpl implements IA_S2Container_AspectDefImpl {}
?>
