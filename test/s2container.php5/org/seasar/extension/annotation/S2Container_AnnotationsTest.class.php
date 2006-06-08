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
 * @package org.seasar.framework.extension.annotation
 * @author klove
 */
class S2Container_AnnotationsTest
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

    function testGetAnnotations() {
        $annos = S2Container_Annotations::getAnnotations('A_Annotations',
                                                         'm01');
        
        $anno = $annos['Annotation01_Annotations'];
        $this->assertEquals($anno->name,'hoge'); 

        $annos = S2Container_Annotations::getAnnotations(
                           new ReflectionClass('A_Annotations'),
                                                         'm02');
        
        $anno = $annos['Annotation01_Annotations'];
        $this->assertEquals($anno->name,'hoge'); 
        $this->assertEquals($anno->year,'2000'); 

        $anno = $annos['Annotation02_Annotations'];
        $this->assertEquals($anno->value[0],'hoge'); 
        $this->assertEquals($anno->value[1],'2000'); 
    }

    function testGetAnnotation() {
        $anno = S2Container_Annotations::getAnnotation(
                         'Annotation01_Annotations',
                         'A_Annotations',
                         'm02');
        $this->assertEquals($anno->name,'hoge'); 
        $this->assertEquals($anno->year,'2000'); 

        try{
            $anno = S2Container_Annotations::getAnnotation(
                         'Annotation03_AnnotationsTests',
                         'A_Annotations',
                         'm02');
            
            $this->assertTrue(false);
        }catch(Exception $e){
            $this->assertTrue(true);
            print "{$e->getMessage()}\n";
        }
    }

    function testIsAnnotationPresent() {
        $ret = S2Container_Annotations::isAnnotationPresent(
                         'Annotation01_Annotations',
                         'A_Annotations',
                         'm02');
        $this->assertEquals($ret,true); 

        $ret = S2Container_Annotations::isAnnotationPresent(
                         'Annotation03_AnnotationsTests',
                         'A_Annotations');
            
        $this->assertFalse($ret);
    }
}

class A_Annotations{

    /**
     * @Annotation01_Annotations
     */
    public function m01(){
        
    }    

    /**
     * @Annotation01_Annotations(name=hoge,year=2000)
     * @Annotation02_Annotations(hoge,2000)
     */
    public function m02(){
        
    }    

}

class Annotation01_Annotations {
    public $name = "hoge";   
}
class Annotation02_Annotations {}
?>
