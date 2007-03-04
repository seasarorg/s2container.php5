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
 * @package org.seasar.framework.extension.annotation.container
 * @author klove
 */
class S2Container_AnnotationContainerTest
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
        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertType('S2Container_AnnotationContainer',$container);

        $container2 = S2Container_AnnotationContainer::getInstance();         
        $this->assertTrue($container === $container2);
    }

    function testAnnotationId() {
        $container = S2Container_AnnotationContainer::getInstance();
        $id = $container->getAnnotationId("Foo","bar");
        $this->assertEquals($id,'Foo:bar');

        $id = $container->getAnnotationId("Foo",null);
        $this->assertEquals($id,'Foo:class');

        $id = $container->getAnnotationId(null,null);
        $this->assertEquals($id,':class');
    }

    function testGetClassAnnotations() {
        $container = S2Container_AnnotationContainer::getInstance();
        $annos = $container->getAnnotations('A_AnnotationContainer');
        $this->assertEquals(count($annos),3);
    }

    function testGetClassAnnotation() {
        $container = S2Container_AnnotationContainer::getInstance();
        $anno = $container->getAnnotation('AnnotationA_AnnotationContainer',
                                           'A_AnnotationContainer');
        $this->assertType('AnnotationA_AnnotationContainer',$anno);
        
        try{
            $anno = $container->getAnnotation('AnnotationC_AnnotationContainer',
                                           'A_AnnotationContainer');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
    }

    function testIsClassAnnotationPresent() {
        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertTrue($container->isAnnotationPresent(
                              'AnnotationA_AnnotationContainer',
                              'A_AnnotationContainer') );
        $this->assertFalse($container->isAnnotationPresent(
                              'AnnotationC_AnnotationContainer',
                              'A_AnnotationContainer') );       
    }

    function testGetMethodAnnotations() {
        $container = S2Container_AnnotationContainer::getInstance();
        $annos = $container->getAnnotations('A_AnnotationContainer','m1');
        $this->assertEquals(count($annos),3);
    }

    function testGetMethodAnnotation() {
        $container = S2Container_AnnotationContainer::getInstance();
        $anno = $container->getAnnotation('AnnotationA_AnnotationContainer',
                                           'A_AnnotationContainer'
                                           ,'m1');
        $this->assertType('AnnotationA_AnnotationContainer',$anno);
        
        try{
            $anno = $container->getAnnotation('AnnotationC_AnnotationContainer',
                                           'A_AnnotationContainer',
                                           'm1');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";
            $this->assertTrue(true);
        }
    }

    function testIsMethodAnnotationPresent() {
        $container = S2Container_AnnotationContainer::getInstance();
        $this->assertTrue($container->isAnnotationPresent(
                              'AnnotationA_AnnotationContainer',
                              'A_AnnotationContainer'),
                              'm1' );
        $this->assertFalse($container->isAnnotationPresent(
                              'AnnotationC_AnnotationContainer',
                              'A_AnnotationContainer'),
                              'm1' );       
    }

    function testSameAnnotationObject() {
        $container = S2Container_AnnotationContainer::getInstance();
        $anno1 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainer',
                            'A_AnnotationContainer');

        $anno2 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainer',
                            'A_AnnotationContainer');

        $anno3 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainer',
                            'A_AnnotationContainer',
                            'm1' );

        $anno4 = $container->getAnnotation(
                            'AnnotationA_AnnotationContainer',
                            'A_AnnotationContainer',
                            'm1' );

        $this->assertTrue($anno1 === $anno2);
        $this->assertTrue($anno3 === $anno4);
        $this->assertTrue($anno1 === $anno2);
        $this->assertFalse($anno1 === $anno3);
        $this->assertFalse($anno2 === $anno4);
    }
}

/**
 * @AnnotationA_AnnotationContainer
 * @AnnotationB_AnnotationContainer ()
 * @AnnotationC_AnnotationContainer (
 * @AnnotationD_AnnotationContainer ()
 * 
 */
class A_AnnotationContainer {

    public function __construct(){}
    
    /**
     * @AnnotationA_AnnotationContainer
     * @AnnotationB_AnnotationContainer ()
     * @AnnotationC_AnnotationContainer (
     * @AnnotationD_AnnotationContainer ()
     * 
     */
    public function m1(){}       
}

class AnnotationA_AnnotationContainer{}
class AnnotationB_AnnotationContainer{}
class AnnotationC_AnnotationContainer{}
class AnnotationD_AnnotationContainer{}
?>
