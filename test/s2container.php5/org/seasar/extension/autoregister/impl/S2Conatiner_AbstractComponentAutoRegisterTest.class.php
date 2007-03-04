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
class S2Conatiner_AbstractComponentAutoRegisterTest
    extends PHPUnit2_Framework_TestCase {

    private static $SAMPLE_DIR;
                               
    public function __construct($name) {
        parent::__construct($name);
        self::$SAMPLE_DIR = dirname(__FILE__)
                          . "/sample/" 
                          . __CLASS__
                          . "/";
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testInstantiate() {
        $register = new Test_S2Container_AbstractComponentAutoRegister();
        $this->assertType('S2Container_AbstractAutoRegister',$register);
    }

    function testRegister() {
        $register = new Test_S2Container_AbstractComponentAutoRegister();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = self::$SAMPLE_DIR
                       . 'A_S2Container_AbstractComponentAutoRegister.class.php';

        $register->register($classFilePath,
                            'A_S2Container_AbstractComponentAutoRegister');
        $cd = $register->findComponentDef('a');
        $cd->init();
        $a = $cd->getComponent();
        $this->assertType('S2Container_DefaultAopProxy',$a);
        $this->assertEquals($a->testTrace(2,3),5);
        $this->assertEquals($a->getData(),1000);
    }
/*
    function testProcessClass() {
        $register = new Test_S2Container_AbstractComponentAutoRegister();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = self::$SAMPLE_DIR
                       . 'A_S2Container_AbstractComponentAutoRegister.class.php';

        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames("^A_");
        $register->addClassPattern($cp);
        
        $register->processClass($classFilePath,
                            'A_S2Container_AbstractComponentAutoRegister');
        $cd = $register->findComponentDef('a');
        $a = $cd->getComponent();
        $this->assertType('S2Container_DefaultAopProxy',$a);

        $classFilePath = self::$SAMPLE_DIR
                       . 'B_S2Container_AbstractComponentAutoRegister.class.php';
        $register->processClass($classFilePath,'b');
        $cd = $register->findComponentDef('b');
        $this->assertEquals($cd,null);        
    }

    function testProcessClassIgnore() {
        $register = new Test_S2Container_AbstractComponentAutoRegister();
        $register->setContainer(new S2ContainerImpl());
        $classFilePath = self::$SAMPLE_DIR
                       . 'A_S2Container_AbstractComponentAutoRegister.class.php';

        $cp = new S2Container_ClassPattern();
        $cp->setShortClassNames("^A_");
        $register->addIgnoreClassPattern($cp);
        $register->addClassPattern($cp);
        
        $register->processClass($classFilePath,
                            'A_S2Container_AbstractComponentAutoRegister');
        $cd = $register->findComponentDef('a');
        $this->assertEquals($cd,null);
    }
    */
}

class Test_S2Container_AbstractComponentAutoRegister
    extends S2Container_AbstractComponentAutoRegister{

    public function registerAll(){}
    
    public function __construct(){
        parent::__construct();   
    }        
    
    public function register($classFilePath, $className) {
        return parent::register($classFilePath, $className);
    }
    
    public function findComponentDef($name){
        return parent::findComponentDef($name);
    }
    
    public function addClassPattern($classPattern) {
        parent::addClassPatternInternal($classPattern);
    }

    public function addIgnoreClassPattern($classPattern) {
        parent::addIgnoreClassPatternInternal($classPattern);
    }    
}
?>
