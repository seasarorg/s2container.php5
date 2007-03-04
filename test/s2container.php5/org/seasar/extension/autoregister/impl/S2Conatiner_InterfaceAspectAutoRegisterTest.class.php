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
class S2Conatiner_InterfaceAspectAutoRegisterTest
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
        $register = new S2Container_InterfaceAspectAutoRegister();
        $this->assertType('S2Container_InterfaceAspectAutoRegister',$register);
    }
    
    function testRegisterAll() {
        $path = self::$SAMPLE_DIR
              . 'a_InterfaceAspectAutoRegister.dicon';
        $container = S2ContainerFactory::create($path);
        $container->init();
        $a = $container->getComponent('a');
        $a->test();
        $this->assertType('A_S2Container_InterfaceAspectAutoRegisterEnhancedByS2AOP',$a);

        $b = $container->getComponent('b');
        $this->assertType('B_S2Container_InterfaceAspectAutoRegister',$b);
    }    
}

class A_S2Container_InterfaceAspectAutoRegister 
    implements IA_S2Container_InterfaceAspectAutoRegister{
    public function test(){
        print __METHOD__ . "\n";  
    }   
}

interface IA_S2Container_InterfaceAspectAutoRegister {
    public function test();
}

class B_S2Container_InterfaceAspectAutoRegister {
    public function test(){
        print __METHOD__ . "\n";  
    }   
}
?>
