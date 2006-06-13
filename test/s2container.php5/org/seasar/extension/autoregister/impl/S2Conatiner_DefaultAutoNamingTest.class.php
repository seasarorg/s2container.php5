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
 * @package org.seasar.framework.extension.autoregister.impl
 * @author klove
 */
class S2Conatiner_DefaultAutoNamingTest
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

    function testInstantiate() {
        $naming = new S2Container_DefaultAutoNaming();
        $this->assertType('S2Container_DefaultAutoNaming',$naming);
    }

    function testMakeDefineName() {
        $naming = new S2Container_DefaultAutoNaming();
        $naming->addReplaceRule("Test$","TEST");
        $val = $naming->makeDefineName("",'ZZzTest');
        $this->assertEquals($val,"zZzTEST");

        $naming->setDecapitalize(false);
        $val = $naming->makeDefineName("",'ZZzTestXxx');
        $this->assertEquals($val,"ZZzTestXxx");
    }
}
?>
