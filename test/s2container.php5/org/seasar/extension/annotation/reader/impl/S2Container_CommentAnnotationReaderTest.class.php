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
 * @package org.seasar.framework.extension.reader.impl
 * @author klove
 */
class S2Container_CommentAnnotationReaderTest
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
        $reader = new S2Container_CommentAnnotationReader();
        $this->assertType('S2Container_AnnotationReader',$reader);
    }

    function testGetAnnotations01() {
        $reader = new S2Container_CommentAnnotationReader();
        $annos = $reader->getAnnotations(
            new ReflectionClass('A_CommentAnnotationReader'),null);
        $this->assertEquals($annos,null);
    }

    function testGetAnnotations02() {
        $reader = new S2Container_CommentAnnotationReader();
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),null);

        $this->assertEquals(count($annos),1);
        $this->assertType('Annotation01_CommentAnnotationReader',
                   $annos['Annotation01_CommentAnnotationReader']);
    }

    function testGetAnnotations03() {
        $reader = new S2Container_CommentAnnotationReader();

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m01');
        $this->assertEquals(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m02');
        $this->assertType('Annotation01_CommentAnnotationReader',
                   $annos['Annotation01_CommentAnnotationReader']);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m03');
        $this->assertEquals(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m04');
        $this->assertEquals(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m05');
        $this->assertEquals(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m06');
        $this->assertType('Annotation01_CommentAnnotationReader',
                   $annos['Annotation01_CommentAnnotationReader']);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m07');
        $this->assertEquals(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m08');
        $this->assertEquals(count($annos),0);


        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m09');
        $this->assertEquals(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m10');
        $this->assertType('Annotation01_CommentAnnotationReader',
                   $annos['Annotation01_CommentAnnotationReader']);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m11');
        $this->assertEquals(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('B_CommentAnnotationReader'),'m12');
        $this->assertEquals(count($annos),0);
    }

    function testGetAnnotations04() {
        $reader = new S2Container_CommentAnnotationReader();

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m01');
        $this->assertEquals(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m02');
        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m03');
        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m04');
        $this->assertEquals(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m05');
        $this->assertEquals(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m06');
        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m07');
        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m08');
        $this->assertEquals(count($annos),0);

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m09');
        $this->assertEquals(count($annos),0);
        
        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m10');
        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m11');
        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');

        $annos = $reader->getAnnotations(
            new ReflectionClass('C_CommentAnnotationReader'),'m12');
        $this->assertEquals(count($annos),0);
    }

    function testGetAnnotations05() {
        $reader = new S2Container_CommentAnnotationReader();

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReader'),'m01');
        $this->assertEquals(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReader'];
        $this->assertEquals($anno->name,'hoge');
        $this->assertEquals($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');
        $this->assertEquals($anno->value[1],'2000');


        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReader'),'m02');
        $this->assertEquals(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReader'];
        $this->assertEquals($anno->name,'hoge');
        $this->assertEquals($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value,'hoge');


        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReader'),'m03');
        $this->assertEquals(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReader'];
        $this->assertEquals($anno->name,'hoge');
        $this->assertEquals($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');
        $this->assertEquals($anno->value[1],'2000');

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReader'),'m04');
        $this->assertEquals(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReader'];
        $this->assertEquals($anno->name,'hoge');
        $this->assertEquals($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');
        $this->assertEquals($anno->value[1],'2000');

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReader'),'m05');
        $this->assertEquals(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReader'];
        $this->assertEquals($anno->name,'hoge');
        $this->assertEquals($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');
        $this->assertEquals($anno->value[1],'2000');

        $annos = $reader->getAnnotations(
            new ReflectionClass('D_CommentAnnotationReader'),'m06');
        $this->assertEquals(count($annos),2);
        $anno = $annos['Annotation02_CommentAnnotationReader'];
        $this->assertEquals($anno->name,'hoge');
        $this->assertEquals($anno->year,'2000');

        $anno = $annos['Annotation01_CommentAnnotationReader'];
        $this->assertEquals($anno->value[0],'hoge');
        $this->assertEquals($anno->value[1],'2000');
    }

    function testGetAnnotations06() {
        $reader = new S2Container_CommentAnnotationReader();

        try{
            $annos = $reader->getAnnotations(
                new ReflectionClass('E_CommentAnnotationReader'),'m01');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";    
            $this->assertTrue(true);
        }
    }

    function testGetAnnotations07() {
        $reader = new S2Container_CommentAnnotationReader();

        try{
            $annos = $reader->getAnnotations(
                new ReflectionClass('E_CommentAnnotationReader'),'m02');
            $this->assertTrue(false);
        }catch(Exception $e){
            print "{$e->getMessage()}\n";    
            $this->assertTrue(true);
        }
        
        $annos = $reader->getAnnotations(
                new ReflectionClass('E_CommentAnnotationReader'),'m03');
        $this->assertEquals($annos['Annotation02_CommentAnnotationReader']->name ,
                           "");
    }

}
    
/**
 * 
 */
class A_CommentAnnotationReader{}
    
/**
 * hoge hoge
 * @Annotation01_CommentAnnotationReader
 */    
class B_CommentAnnotationReader{

    /** xxx */
    public function m01(){}

    /**   @Annotation01_CommentAnnotationReader  */
    public function m02(){}

    /** @Annotation01_CommentAnnotationReader  xxx */
    public function m03(){}

    /** xxx @Annotation01_CommentAnnotationReader */
    public function m04(){}


    /** xxx
     */
    public function m05(){}

    /**   @Annotation01_CommentAnnotationReader
     */
    public function m06(){}

    /** @Annotation01_CommentAnnotationReader  xxx 
     */
    public function m07(){}

    /** xxx @Annotation01_CommentAnnotationReader 
     */
    public function m08(){}


    /** 
     * xxx */
    public function m09(){}

    /**   
     *    @Annotation01_CommentAnnotationReader  */
    public function m10(){}

    /** 
     *    @Annotation01_CommentAnnotationReader  xxx */
    public function m11(){}

    /** 
     * xxx @Annotation01_CommentAnnotationReader */
    public function m12(){}
}

class C_CommentAnnotationReader{

    /** xxx */
    public function m01(){}

    /**   @Annotation01_CommentAnnotationReader ('hoge','2000') */
    public function m02(){}

    /** @Annotation01_CommentAnnotationReader  ('hoge','2000')xxx */
    public function m03(){}

    /** xxx @Annotation01_CommentAnnotationReader ('hoge','2000')*/
    public function m04(){}


    /** xxx
     */
    public function m05(){}

    /**   @Annotation01_CommentAnnotationReader ('hoge','2000')
     */
    public function m06(){}

    /** @Annotation01_CommentAnnotationReader('hoge','2000')  xxx 
     */
    public function m07(){}

    /** xxx @Annotation01_CommentAnnotationReader('hoge','2000')
     */
    public function m08(){}


    /** 
     * xxx */
    public function m09(){}

    /**   
     *    @Annotation01_CommentAnnotationReader('hoge','2000')  */
    public function m10(){}

    /** 
     *    @Annotation01_CommentAnnotationReader ('hoge','2000') xxx */
    public function m11(){}

    /** 
     * xxx @Annotation01_CommentAnnotationReader('hoge','2000') */
    public function m12(){}
}


class D_CommentAnnotationReader{
    /** 
     * @Annotation02_CommentAnnotationReader(name='hoge',year='2000')
     * @Annotation01_CommentAnnotationReader('hoge','2000')
     */
    public function m01(){}

    /** 
     * @Annotation02_CommentAnnotationReader(name=hoge , 'year'=2000)
     * @Annotation01_CommentAnnotationReader('hoge')
     */
    public function m02(){}

    /** 
     * @Annotation02_CommentAnnotationReader(
     *                    name='hoge',year='2000')
     * @Annotation01_CommentAnnotationReader('hoge',
     *                                            '2000')
     */
    public function m03(){}

    /** 
     * @Annotation02_CommentAnnotationReader(
     *                    name='hoge',
     * 
     *                    year='2000')
     * @Annotation01_CommentAnnotationReader('hoge',
     * 
     *                                            '2000')
     */
    public function m04(){}

    /** 
     * Zzzz Zzzz Zzzz
     * Zzzz Zzzz Zzzz
     * @Annotation02_CommentAnnotationReader(
     *                    name=
     *                    'hoge',
     *                    year='2000')
     * Zzzz Zzzz Zzzz
     * @Annotation01_CommentAnnotationReader('hoge',
     *                                            '2000')
     * Zzzz Zzzz Zzzz
     * Zzzz Zzzz Zzzz
     */
    public function m05(){}

    /** 
     * @Zzzz Zzzz Zzzz
     * @Zzzz Zzzz Zzzz
     * @Annotation02_CommentAnnotationReader(
     *                    name=
     *                    'hoge',
     *                    year='2000')
     * @Zzzz Zzzz Zzzz
     * @Annotation01_CommentAnnotationReader('hoge',
     *                                            '2000')
     * @Zzzz Zzzz Zzzz
     * @Zzzz Zzzz Zzzz
     */
    public function m06(){}
}

class E_CommentAnnotationReader{
    /** 
     * @Annotation02_CommentAnnotationReader(name='hoge',year,'2000')
     */
    public function m01(){}

    /** 
     * @Annotation02_CommentAnnotationReader( ='hoge')
     */
    public function m02(){}

    /** 
     * @Annotation02_CommentAnnotationReader( name = )
     */
    public function m03(){}
}

class Annotation01_CommentAnnotationReader{}
class Annotation02_CommentAnnotationReader{}

?>
