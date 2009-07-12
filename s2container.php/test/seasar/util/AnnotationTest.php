<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
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
/**
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar\util;
class AnnotationTest extends \PHPUnit_Framework_TestCase {

    public function testA() {
        Annotation::$CONSTANT = true;
        $result = Annotation::get(new \ReflectionClass('\seasar\util\Hoge_CommentAnnotationTest'), 'hoge');
        $this->assertEquals($result, array('year' => 2007));
        $result = Annotation::get(new \ReflectionClass('\seasar\util\Hoge_CommentAnnotationTest'), '@huga');
        $this->assertEquals($result, array('year' => 2008));
    }

    
    public function testHasAnnotation(){
        $clazzB = new \ReflectionClass(__NAMESPACE__ . '\AnnoTestB_AnnotationTest');
        $ret = Annotation::has($clazzB, \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION);
        $this->assertFalse($ret);
        $ret = Annotation::has($clazzB, \seasar\container\factory\ComponentDefBuilder::COMPONENT_ANNOTATION);
        $this->assertTrue($ret);
        $ret = Annotation::has($clazzB->getMethod('b'), \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION);
        $this->assertTrue($ret);
        $ret = Annotation::has($clazzB->getMethod('a'), \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        $this->assertTrue($ret);

        $ret = Annotation::has($clazzB->getProperty('seasar'), \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        $this->assertTrue($ret);
    }

    public function testGetAnnotation(){
        $clazzB = new \ReflectionClass(__NAMESPACE__ . '\AnnoTestB_AnnotationTest');
        try{
            $ret = Annotation::get($clazzB, \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION);
        } catch(\Exception $e) {
            $this->assertEquals(1, preg_match("/not found on/", $e->getMessage()));
        }
        $propRef = $clazzB->getProperty('year');
        try{
            $ret = Annotation::get($propRef, \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        } catch(\Exception $e) {
            $this->assertEquals(1, preg_match("/not found on/", $e->getMessage()));
        }
        $propRef = $clazzB->getProperty('name');
        $ret = Annotation::get($propRef, \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        $this->assertEquals($ret, array('hoge'));
        
        $clazzB = new \ReflectionClass(__NAMESPACE__ . '\AnnoTestB_AnnotationTest');
        $ret = Annotation::get($clazzB, \seasar\container\factory\ComponentDefBuilder::COMPONENT_ANNOTATION);
        $this->assertEquals($ret['name'], 'b');

        $ret = Annotation::get($clazzB->getMethod('a'), \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        $this->assertEquals($ret[0], 'abc');

        $ret = Annotation::get($clazzB->getProperty('seasar'), \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        $this->assertEquals($ret[0], 'しーさ');
    }

    public function testFormatCommentLine1(){
        $comment = ' /** abc  */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /** abc@def
                      * ghi
                      *
                      * jkl
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc@def  ghi   jkl');
    }

    public function testFormatCommentLine2(){
        $comment = ' /**
                      * abc
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), '');

        $comment = ' /**
                      *
                      * abc
                      *
                      * def
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), '');

        $comment = ' /**
                      * @abc
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), '@abc');

        $comment = ' /**
                      * abc
                      * def
                      *
                      * @abc
                      * def
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), '@abc  def');

        $comment = ' /**
                      * zzz
                      *
                      * abc@
                      * @abc
                      * def
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc@  @abc  def');
    }

    public function testFormatCommentLine3(){
        $comment = ' /**
                      * abc */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc **/ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc *');

        $comment = ' /**
                      * aaa
                      * bbb
                      *
                      * abc **/ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc *');
    }

    public function testGetCommentAnnotation(){
        $clazz = new \ReflectionClass(__NAMESPACE__ . '\C_AnnotationTest');

        $ret = Annotation::getCommentAnnotation($clazz, \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION);
        $this->assertEquals($ret['val'], 100);

        $propRef = $clazz->getProperty('foo');
        $ret = Annotation::getCommentAnnotation($propRef, \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION);
        $this->assertEquals($ret['val'], 200);

        $propRef = $clazz->getMethod('bar');
        $ret = Annotation::getCommentAnnotation($propRef, \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION);
        $this->assertEquals($ret['val'], 300);

        try{
            $ret = Annotation::getCommentAnnotation($clazz, \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION);
        } catch(\Exception $e) {
            $this->assertEquals(1, preg_match("/not found on/", $e->getMessage()));
        }
    }

    public function testHasCommentAnnotation(){
        $clazz = new \ReflectionClass(__NAMESPACE__ . '\C_AnnotationTest');

        $this->assertTrue(Annotation::hasCommentAnnotation($clazz, \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION));
        $this->assertFalse(Annotation::hasCommentAnnotation($clazz, \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION));

        $this->assertTrue(Annotation::hasCommentAnnotation($clazz->getProperty('foo'), \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION));
        $this->assertFalse(Annotation::hasCommentAnnotation($clazz->getProperty('foo'), \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION));

        $this->assertTrue(Annotation::hasCommentAnnotation($clazz->getMethod('bar'), \seasar\container\factory\ComponentDefBuilder::ASPECT_ANNOTATION));
        $this->assertFalse(Annotation::hasCommentAnnotation($clazz->getMethod('bar'), \seasar\container\factory\ComponentDefBuilder::BINDING_ANNOTATION));
    }

    public function testGetConstantAnnotation(){
        $clazz = new \ReflectionClass(__NAMESPACE__ . '\D_AnnotationTest');

        $ret = Annotation::getConstantAnnotation($clazz, 'Aspect');
        $this->assertEquals($ret['val'], 100);

        $propRef = $clazz->getProperty('foo');
        $ret = Annotation::getConstantAnnotation($propRef, 'Aspect');
        $this->assertEquals($ret['val'], 200);

        $propRef = $clazz->getMethod('bar');
        $ret = Annotation::getConstantAnnotation($propRef, 'Aspect');
        $this->assertEquals($ret['val'], 300);

        try{
            $ret = Annotation::getConstantAnnotation($clazz, 'Binding');
        } catch(\Exception $e) {
            $this->assertEquals(1, preg_match("/not found on/", $e->getMessage()));
        }
    }

    public function testHasConstantAnnotation(){
        $clazz = new \ReflectionClass(__NAMESPACE__ . '\D_AnnotationTest');

        $this->assertTrue(Annotation::hasConstantAnnotation($clazz, 'Aspect'));
        $this->assertFalse(Annotation::hasConstantAnnotation($clazz, 'Binding'));

        $this->assertTrue(Annotation::hasConstantAnnotation($clazz->getProperty('foo'), 'Aspect'));
        $this->assertFalse(Annotation::hasConstantAnnotation($clazz->getProperty('foo'), 'Binding'));

        $this->assertTrue(Annotation::hasConstantAnnotation($clazz->getMethod('bar'), 'Aspect'));
        $this->assertFalse(Annotation::hasConstantAnnotation($clazz->getMethod('bar'), 'Binding'));
    }

    public function testDelmita(){
        $clazz = new \ReflectionClass(__NAMESPACE__ . '\E_AnnotationTest');

        $this->assertEquals(array(100), Annotation::getCommentAnnotation($clazz, '@Hoge'));
        $this->assertEquals(array(200), Annotation::getCommentAnnotation($clazz, '@Huga'));
        $this->assertEquals(array(1, 2, 'a'), Annotation::getCommentAnnotation($clazz, '@Bar'));
    }

    public function testConstructAnnotationKey(){
        $this->assertEquals(array('Hoge', '@Hoge'), Annotation::constructAnnotationKey('@Hoge'));
        $this->assertEquals(array('Hoge', '@Hoge'), Annotation::constructAnnotationKey('Hoge'));
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

/**
 * @huga('year' => 2008);
 */
class Hoge_CommentAnnotationTest {
    const hoge = "'year' => 2007";
}


class AnnoTestA_AnnotationTest {
    public static function setSetA($a){}
    public static function a(){}
}

/**
 * @S2Component('name' => 'b');
 */
class AnnoTestB_AnnotationTest {

    public $year = null;

    /**
     * @S2Binding('hoge');
     */
    public $name = null;

    /**
     * @S2Binding('しーさ');
     */
    public $seasar = null;

    /**
     * @S2Binding('1000');
     */
    public static function setSetB($b){}

    /**
     * @S2Binding('abc');
     */
    public static function a(){}

    /**
     * @S2Aspect('dao.interceptor');
     */
    public static function b(){}
}

class AnnoTestC_AnnotationTest {
    /**
     * @S2Aspect('interceptor' => 'new \seasar\aop\interceptor\TraceInterceptor');
     */
    public function hoge(){}
}

/**
 * @S2Component('name' => 'd');
 */
class AnnoTestD_AnnotationTest extends AnnoTestC_AnnotationTest{}

class AnnoTestE_AnnotationTest {
    public function hoge(){}
}

interface IAnnoTestF_AnnotationTest{
    public function hoge();
}

/**
 * @S2Component('name' => 'annoTestF');
 */
class AnnoTestF_AnnotationTest implements IAnnoTestF_AnnotationTest {
    public function hoge(){}
}

class A_AnnotationTest {
    /**
     * @S2Aspect();
     */
    public function hoge(){}

    /**
     * @S2Aspect(
     */
    public function huga(){}

    /**
     * @S2Aspect
     */
    public function bar(){}
}


/**
 * @S2Aspect
 */
class B_AnnotationTest {
    /**
     *@S2Aspect
     */   
    public function bar(){}
}

/**
 * @S2Aspect('val' => 100);
 */
class C_AnnotationTest {

    /**
     * @S2Aspect('val' => 200);
     */
    private $foo;

    /**
     * @S2Aspect('val' => 300);
     */   
    public function bar(){}
}

class D_AnnotationTest {
    const Aspect = '"val" => 100';

    const foo_Aspect = '"val" => 200';
    private $foo;

    const bar_Aspect = '"val" => 300';
    public function bar(){}
}

/**
 * @Hoge(100 );
 * @Huga(200)
 * @Bar (1, 2, 'a')
 */
class E_AnnotationTest {}
