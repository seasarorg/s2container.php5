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
 * @package org.seasar.framework.container.factory
 * @author klove
 */
class S2Container_SingletonS2ContainerFactoryTest
    extends PHPUnit2_Framework_TestCase {

    private $diconDir;
    public function __construct($name) {
        parent::__construct($name);
        $this->diconDir = dirname(__FILE__) . '/dicon/' . __CLASS__;
        if (!defined('DICON_DIR_S2Container_SingletonS2ContainerFactoryTest')){
            define('DICON_DIR_S2Container_SingletonS2ContainerFactoryTest',
                   $this->diconDir);
        }
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
    }

    public function tearDown() {
        print "\n";
    }

    function testInit() {

        define('S2CONTAINER_PHP5_APP_DICON',
                $this->diconDir . '/app.dicon');
        print "define app.dicon [ " . S2CONTAINER_PHP5_APP_DICON . " ] \n";
   
        S2Container_SingletonS2ContainerFactory::init();
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $a = $container->getComponent('a');
        $this->assertType('A_S2Container_SingletonS2ContainerFactory',$a);
    }

    function testInitWithPath() {
        S2Container_SingletonS2ContainerFactory::init($this->diconDir . '/testInitWithPath.dicon');
        $container = S2Container_SingletonS2ContainerFactory::getContainer();
        $this->assertNotNull($container);
       
        $a = $container->getComponent('a');
        $this->assertType('A_S2Container_SingletonS2ContainerFactory',$a);

        $b = $container->getComponent('testInitWithPath.b');
        $this->assertType('A_S2Container_SingletonS2ContainerFactory',$b);
    }

    function testInitWithPathNotExist() {
        $path = 'zz:/tmp/test.dicon';  
        try{  
            S2Container_SingletonS2ContainerFactory::init($path);
        }catch(Exception $e){
            $this->assertType('S2Container_S2RuntimeException',$e);  
            print $e->getMessage() ."\n";
        }    
    }
}

class A_S2Container_SingletonS2ContainerFactory {}
?>
