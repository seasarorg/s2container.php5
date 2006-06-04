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
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class S2Container_DefaultDestroyMethodAssemblerTest 
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

    function testDestroy() {
        $container = new S2ContainerImpl();
        $container->register('G_S2Container_DefaultDestroyMethodAssembler','g');

        $cd = $container->getComponentDef('g');
        $me = new S2Container_DestroyMethodDefImpl('finish');
        $cd->addDestroyMethodDef($me);

        $me = new S2Container_DestroyMethodDefImpl('finish2');
        $arg = new S2Container_ArgDefImpl();
        $arg->setValue("destroy test.");
        $me->addArgDef($arg);
        $cd->addDestroyMethodDef($me);

        $g = $container->init();
          
        $container->destroy();
    }
}

interface IG_S2Container_DefaultDestroyMethodAssembler {}
class G_S2Container_DefaultDestroyMethodAssembler
    implements IG_S2Container_DefaultDestroyMethodAssembler {
    function finish(){
        print __METHOD__ . " called as destroy method. \n";
    }

    function finish2($msg){
        print __METHOD__ . " called as destroy method. [$msg] \n";
    }
}
?>
