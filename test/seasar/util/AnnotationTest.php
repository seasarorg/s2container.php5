<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar::util;
class AnnotationTest extends ::PHPUnit_Framework_TestCase {

    public function testA() {
        Annotation::$CONSTANT = true;
        $result = Annotation::get(new ReflectionClass('seasar::util::Hoge_CommentAnnotationTest'), 'hoge');
        $this->assertEquals($result, array('year' => 2007));
        $result = Annotation::get(new ReflectionClass('seasar::util::Hoge_CommentAnnotationTest'), '@huga');
        $this->assertEquals($result, array('year' => 2008));
    }

    
    public function testHasAnnotation(){
        $clazzB = new ReflectionClass(__NAMESPACE__ . '::AnnoTestB_AnnotationTest');
        $ret = Annotation::has($clazzB, seasar::container::S2ApplicationContext::ASPECT_ANNOTATION);
        $this->assertFalse($ret);
        $ret = Annotation::has($clazzB, seasar::container::S2ApplicationContext::COMPONENT_ANNOTATION);
        $this->assertTrue($ret);
        $ret = Annotation::has($clazzB->getMethod('b'), seasar::container::S2ApplicationContext::ASPECT_ANNOTATION);
        $this->assertTrue($ret);
        $ret = Annotation::has($clazzB->getMethod('a'), seasar::container::S2ApplicationContext::BINDING_ANNOTATION);
        $this->assertTrue($ret);

        $ret = Annotation::has($clazzB->getProperty('seasar'), seasar::container::S2ApplicationContext::BINDING_ANNOTATION);
        $this->assertTrue($ret);
    }

    public function testGetAnnotation(){
        $clazzB = new ReflectionClass(__NAMESPACE__ . '::AnnoTestB_AnnotationTest');
        try{
            $ret = Annotation::get($clazzB, seasar::container::S2ApplicationContext::ASPECT_ANNOTATION);
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        $propRef = $clazzB->getProperty('year');
        try{
            $ret = Annotation::get($propRef, seasar::container::S2ApplicationContext::BINDING_ANNOTATION);
            $this->fail();
        } catch(Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        $propRef = $clazzB->getProperty('name');
        $ret = Annotation::get($propRef, seasar::container::S2ApplicationContext::BINDING_ANNOTATION);
        $this->assertEquals($ret, array('hoge'));
        
        $clazzB = new ReflectionClass(__NAMESPACE__ . '::AnnoTestB_AnnotationTest');
        $ret = Annotation::get($clazzB, seasar::container::S2ApplicationContext::COMPONENT_ANNOTATION);
        $this->assertEquals($ret['name'], 'b');

        $ret = Annotation::get($clazzB->getMethod('a'), seasar::container::S2ApplicationContext::BINDING_ANNOTATION);
        $this->assertEquals($ret[0], 'abc');

        $ret = Annotation::get($clazzB->getProperty('seasar'), seasar::container::S2ApplicationContext::BINDING_ANNOTATION);
        $this->assertEquals($ret[0], 'しーさ');
    }

    public function testFormatCommentLine(){
        $comment = ' /** abc  */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc
                      */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc */ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc **/ ';
        $format = Annotation::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

/**
 * @huga('year' => 2008)
 */
class Hoge_CommentAnnotationTest {
    const hoge = "'year' => 2007";
}


class AnnoTestA_AnnotationTest {
    public static function setSetA($a){}
    public static function a(){}
}

/**
 * @S2Component('name' => 'b')
 */
class AnnoTestB_AnnotationTest {

    public $year = null;

    /**
     * @S2Binding('hoge')
     */
    public $name = null;

    /**
     * @S2Binding('しーさ')
     */
    public $seasar = null;

    /**
     * @S2Binding('1000')
     */
    public static function setSetB($b){}

    /**
     * @S2Binding('abc')
     */
    public static function a(){}

    /**
     * @S2Aspect('dao.interceptor')
     */
    public static function b(){}
}

class AnnoTestC_AnnotationTest {
    /**
     * @S2Aspect('interceptor' => 'new seasar::aop::interceptor::TraceInterceptor')
     */
    public function hoge(){}
}

/**
 * @S2Component('name' => 'd')
 */
class AnnoTestD_AnnotationTest extends AnnoTestC_AnnotationTest{}

class AnnoTestE_AnnotationTest {
    public function hoge(){}
}

interface IAnnoTestF_AnnotationTest{
    public function hoge();
}

/**
 * @S2Component('name' => 'annoTestF')
 */
class AnnoTestF_AnnotationTest implements IAnnoTestF_AnnotationTest {
    public function hoge(){}
}
