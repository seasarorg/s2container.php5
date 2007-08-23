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
 * @package org.seasar.framework.extension.factory
 * @author klove
 */
class S2Container_AnnotationFactoryTest
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

    function testCreate() {
        $annoObj = S2Container_AnnotationFactory::create('A_AnnotationFactory');
        $this->assertEquals($annoObj,null);

        $annoObj = S2Container_AnnotationFactory::create('B_AnnotationFactory');
        $this->assertType('B_AnnotationFactory',$annoObj);

        $annoObj = S2Container_AnnotationFactory::create('C_AnnotationFactory',
                     array('hoge'));
        $this->assertType('C_AnnotationFactory',$annoObj);
        $this->assertEquals($annoObj->value,'hoge');

        $annoObj = S2Container_AnnotationFactory::create('C_AnnotationFactory',
                     array('hoge','2005'));
        $this->assertEquals($annoObj->value,array('hoge','2005'));

        $annoObj = S2Container_AnnotationFactory::create('C_AnnotationFactory',
                     array('name'=>'hoge','year'=>'2005'),
                     S2Container_AnnotationFactory::ARGS_TYPE_HASH);
        $this->assertEquals($annoObj->name,'hoge');
        $this->assertEquals($annoObj->year,'2005');
    }
}

class B_AnnotationFactory {}

class C_AnnotationFactory {}
?>
