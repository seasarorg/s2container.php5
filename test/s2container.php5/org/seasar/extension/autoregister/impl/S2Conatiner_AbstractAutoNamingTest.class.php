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
 * @package org.seasar.framework.extension.autoregister.impl
 * @author klove
 */
class S2Conatiner_AbstractAutoNamingTest
    extends PHPUnit_Framework_TestCase {

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
        $naming = new AutoNaming_S2Container_AbstractAutoNaming();
        $this->assertTrue($naming instanceof S2Container_AutoNaming);
        $this->assertType('S2Container_AbstractAutoNaming',$naming);
        $this->assertEquals(count($naming->getReplaceRules()),2);
    }

    function testSetCustomizedName() {
        $naming = new AutoNaming_S2Container_AbstractAutoNaming();
        $naming->setCustomizedName("Emp","Employ");
        $this->assertEquals($naming->defineName("","Emp"),
                            "Employ");
    }

    function testClearReplaceRule() {
        $naming = new AutoNaming_S2Container_AbstractAutoNaming();
        $naming->setCustomizedName("Emp","Employ");
        $this->assertEquals(count($naming->getCustomizedNames()),1);
        $this->assertEquals(count($naming->getReplaceRules()),2);
        $naming->clearReplaceRule();
        $this->assertEquals(count($naming->getCustomizedNames()),0);
        $this->assertEquals(count($naming->getReplaceRules()),0);
    }

    function testApplyRule() {
        $naming = new AutoNaming_S2Container_AbstractAutoNaming();
        $this->assertEquals('hoge',$naming->testApplyRule("HogeBean"));
        $this->assertEquals('hoge',$naming->testApplyRule("HogeImpl"));
        $naming->clearReplaceRule();

        $naming->addReplaceRule('Bar','BAR');
        $this->assertEquals('fooBARFoo',$naming->testApplyRule("FooBarFoo"));
        $naming->clearReplaceRule();

        $naming->addReplaceRule('^Bar$','Bbar');
        $this->assertEquals('bbar',$naming->testApplyRule("Bar"));
        $this->assertEquals('xxx',$naming->testApplyRule("Xxx"));
        $naming->clearReplaceRule();
    }

    function testDecapitalizeFalse() {
        $naming = new AutoNaming_S2Container_AbstractAutoNaming();
        $naming->setDecapitalize(false);        
        $this->assertEquals('Hoge',$naming->testApplyRule("HogeBean"));
    }
}

class AutoNaming_S2Container_AbstractAutoNaming 
    extends S2Container_AbstractAutoNaming{
    
    function __construct(){
        parent::__construct();    
    }

    public function testApplyRule($name){
        return parent::applyRule($name);
    }

    public function getCustomizedNames(){
        return $this->customizedNames;
    }

    public function getReplaceRules(){
        return $this->replaceRules;
    }
    
    public function makeDefineName($directoryPath, $shortClassName) {
        return $shortClassName;
    }        
        
}
?>
