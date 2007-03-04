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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.extension.cache
 * @author nowel
 */
abstract class S2Container_AbstractCacacheSupportTest
extends PHPUnit2_Framework_TestCase {
    
    protected $path_a = null;
    protected $path_b = null;
    protected $path_c = null;
    protected $support = null;
    
    public function __construct($name){
        parent::__construct($name);
    }
    
    protected abstract function init();
    protected abstract function shutdown();
    
    public function setUp(){
        echo __CLASS__ . "::{" . $this->getName() . "}" . PHP_EOL;
        $this->path_a = dirname(__FILE__) . "/testA.dicon";
        $this->path_b = dirname(__FILE__) . "/testB.dicon";
        $this->path_c = dirname(__FILE__) . "/testC.dicon";
        $this->init();
    }
    
    public function tearDown() {
        $this->path_a = null;
        $this->path_b = null;
        $this->path_c = null;
        $this->shutdown();
    }
    
    public function testContainerCache(){
        $this->assertFalse($this->support->isContainerCaching($this->path_a));
        $this->assertFalse($this->support->isContainerCaching($this->path_b));
        $this->assertFalse($this->support->isContainerCaching($this->path_b));
        $container_a = S2ContainerFactory::create($this->path_a);
        $container_b = S2ContainerFactory::create($this->path_b);
        $container_c = S2ContainerFactory::create($this->path_c);
        
        $this->support->saveContainerCache(serialize($container_a));
        $this->support->saveContainerCache(serialize($container_b));
        $this->support->saveContainerCache(serialize($container_c));

        $this->assertNotNull($this->support->loadContainerCache($this->path_a));
        $this->assertNotNull($this->support->loadContainerCache($this->path_b));
        $this->assertNotNull($this->support->loadContainerCache($this->path_c));
    }
    
    public function testAopCache(){
    }
    
}

class A_S2Container_AbstractCacacheSupportTest {
}

?>