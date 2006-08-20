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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id:$
/**
 * @package org.seasar.framework.extension.cache
 * @author nowel
 */
class S2ContainerMemcacheFactoryTest extends PHPUnit2_Framework_TestCase {
    
    private $path_a;
    private $path_b;
    private $path_c;
    private $host;
    
    public function __construct($name) {
        parent::__construct($name);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
        $this->path_a = dirname(__FILE__) . "/testA.dicon";
        $this->path_b = dirname(__FILE__) . "/testB.dicon";
        $this->path_c = dirname(__FILE__) . "/testC.dicon";
        // memcached host
        $this->host = 'localhost';
    }

    public function tearDown() {
        print "\n";
        $this->path_a = null;
        $this->path_b = null;
        $this->path_c = null;
        $this->host = null;
    }
    
    public function testContainerCreate(){
        $option = array('host' => $this->host);
        S2ContainerMemcacheFactory::initialize($option);
        $container1 = S2ContainerMemcacheFactory::create($this->path_a);
        $container2 = S2ContainerFactory::create($this->path_a);
        
        $this->assertNotNull($container1);
        $this->assertEquals($container1, $container2);
        $this->assertEquals($container1->getComponent("a"),
                            $container2->getComponent("a"));
    }
    
    public function testCachedContainerCreate(){
        $option = array('host' => $this->host);
        S2ContainerMemcacheFactory::initialize($option);
        $container1 = S2ContainerMemcacheFactory::create($this->path_b);
        $container2 = S2ContainerMemcacheFactory::create($this->path_b);
        $container3 = S2ContainerMemcacheFactory::create($this->path_b, "b_key");
        $container4 = S2ContainerMemcacheFactory::create($this->path_b, "B_key");
        
        $this->assertEquals($container1, $container2);
        $this->assertEquals($container3, $container4);
        $this->assertEquals($container1, $container3);
    }
    
    public function testAnotherDiconCacheContainerCreate(){
        $option = array('host' => $this->host);
        S2ContainerMemcacheFactory::initialize($option);
        $container1 = S2ContainerMemcacheFactory::create($this->path_b);
        $container2 = S2ContainerMemcacheFactory::create($this->path_c);
        $container3 = S2ContainerMemcacheFactory::create($this->path_c, "c_key");
        $container4 = S2ContainerMemcacheFactory::create($this->path_b, "c_key");
        
        $this->assertNotEquals($container1, $container2);
        $this->assertEquals($container3, $container4);
        $this->assertNotEquals($container1, $container3);
        $this->assertNotEquals($container1, $container4);
    }

    public function testAnotherDiconCacheContainerSame(){
        $option = array('host' => $this->host);
        S2ContainerMemcacheFactory::initialize($option);
        $container1 = S2ContainerMemcacheFactory::create($this->path_a, "d_key");
        $container2 = S2ContainerMemcacheFactory::create($this->path_c, "d_key");

        $this->assertEquals($container1, $container2);
    }

    public function testMemcacheOption1(){
        $option1 = array('host' => $this->host, 'cache_compress' => false);
        S2ContainerMemcacheFactory::initialize($option1);
        $a = S2ContainerMemcacheFactory::create($this->path_a);
        $option2 = array('host' => $this->host, 'cache_compress' => true);
        S2ContainerMemcacheFactory::initialize($option2);
        $b = S2ContainerMemcacheFactory::create($this->path_a);
        $option3 = array('host' => $this->host);
        S2ContainerMemcacheFactory::initialize($option3);
        $c = S2ContainerMemcacheFactory::create($this->path_a);
        
        $this->assertEquals($a, $b);
        $this->assertEquals($a, $c);
    }
    
    public function testMemcacheOption2(){
        $option = array('host' => $this->host, 'cache_expire' => 1);
        S2ContainerMemcacheFactory::initialize($option);
        $a = S2ContainerMemcacheFactory::create($this->path_a, 'aaaa');
        S2ContainerMemcacheFactory::create($this->path_b, 'a');
        S2ContainerMemcacheFactory::create($this->path_b, 'aa');
        S2ContainerMemcacheFactory::create($this->path_b, 'a.a');
        S2ContainerMemcacheFactory::create($this->path_c, '');
        S2ContainerMemcacheFactory::create($this->path_c, 'a.a');
        S2ContainerMemcacheFactory::create($this->path_c, 'aa');
        S2ContainerMemcacheFactory::create($this->path_a, 'aa.a');
        S2ContainerMemcacheFactory::create($this->path_a, 1);
        S2ContainerMemcacheFactory::create($this->path_a, 2);
        S2ContainerMemcacheFactory::initialize($option);
        $b = S2ContainerMemcacheFactory::create($this->path_a, 'aaaa');

        $this->assertEquals($a, $b);
    }
    
    public function testMemcacheOption3(){
        $option1 = array('host' => $this->host, 'port' => 11211);
        S2ContainerMemcacheFactory::initialize($option1);
        $a = S2ContainerMemcacheFactory::create($this->path_a, 'aaaa');
        $option2 = array('host' => $this->host, 'port' => "11211");
        S2ContainerMemcacheFactory::initialize($option2);
        $b = S2ContainerMemcacheFactory::create($this->path_a, 'aaaa');
        
        $this->assertNotNull($a);
        $this->assertNotNull($b);
    }
    
    public function testSameContainerFactory(){
        $option = array('host' => $this->host);
        $factory1 = S2ContainerMemcacheFactory::getInstance($option);
        $factory2 = S2ContainerMemcacheFactory::getInstance($option);
        $factory3 = $factory2->getInstance($option);
        
        $this->assertEquals($factory1, $factory2);
        $this->assertEquals($factory2, $factory3);
        
        $this->assertNotNull($factory1->create($this->path_a));
        $this->assertNotNull($factory2->create($this->path_a));
        $this->assertNotNull($factory3->create($this->path_a));
    }
    
    public function testSameContainer(){
        $option = array('host' => $this->host);
        
        S2ContainerMemcacheFactory::initialize($option);
        $container1 = S2ContainerMemcacheFactory::create($this->path_a);
        
        $factory = S2ContainerMemcacheFactory::getInstance($option);
        $container2 = $factory->create($this->path_a);
        
        $this->assertEquals($container1, $container2);
    }

}

class A_S2Container_Cache {
}

?>
