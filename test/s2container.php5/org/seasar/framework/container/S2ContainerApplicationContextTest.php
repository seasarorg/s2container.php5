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
 * S2Base.PHP5 with Zf
 * 
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @package    org.seasar.framework.container
 * @author     klove
 */
class S2ContainerApplicationContextTest extends PHPUnit2_Framework_TestCase {
    public function __construct($name) {
        parent::__construct($name);
    }

    public function testImportException(){
        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/XXX');
        $this->assertEquals(S2ContainerApplicationContext::$CLASSES, array());
    }

    public function testImportClass(){
        S2ContainerApplicationContext::import($this->sampleDir);
        $this->assertEquals(S2ContainerApplicationContext::$CLASSES, array());

        S2ContainerApplicationContext::import($this->sampleDir . '/sample/aaa/Bar_S2ContainerApplicationContext.php');
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertEquals($classes, array('Bar_S2ContainerApplicationContext'));

        S2ContainerApplicationContext::import($this->sampleDir . '/sample/aaa');
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertEquals($classes, array('Bar_S2ContainerApplicationContext','Foo_S2ContainerApplicationContext'));

        S2ContainerApplicationContext::$CLASSES = array();

        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(count($classes) === 4);
    }

    public function testImportDicon(){
        S2ContainerApplicationContext::$CLASSES = array();

        S2ContainerApplicationContext::import($this->sampleDir . '/dicon/aaa.dicon');
        $dicons = array_keys(S2ContainerApplicationContext::$DICONS);
        $this->assertEquals($dicons, array('aaa.dicon'));

        S2ContainerApplicationContext::import($this->sampleDir . '/dicon', true);
        $dicons = array_keys(S2ContainerApplicationContext::$DICONS);
        $this->assertEquals($dicons, array('aaa.dicon','ccc.dicon'));
    }

    public function testImportPear(){
        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', true, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
    }

    public function testImportInternal(){
        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::importInternal('/A/B/C/D.php');
        $this->assertEquals(S2ContainerApplicationContext::$CLASSES, array('D' => '/A/B/C/D.php'));

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::importInternal('/A/B/C/D.php', true, array('B','C'));
        $this->assertEquals(S2ContainerApplicationContext::$CLASSES, array('B_C_D' => '/A/B/C/D.php'));

        S2ContainerApplicationContext::$DICONS = array();
        S2ContainerApplicationContext::importInternal('/A/B/C/D.dicon', true, array('B','C'));
        $this->assertEquals(S2ContainerApplicationContext::$DICONS, array('D.dicon' => '/A/B/C/D.dicon'));
    }

    public function testCreate(){
        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::$DICONS = array();
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 0);

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 3);

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        S2ContainerApplicationContext::addIncludePattern('/Bar_/');
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 1);

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        S2ContainerApplicationContext::addIncludePattern(array('/Bar_/','/Hoge_/'));
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 2);

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        S2ContainerApplicationContext::setIncludePattern(array());
        S2ContainerApplicationContext::addExcludePattern('/Hoge_/');
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 2);

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        S2ContainerApplicationContext::addIncludePattern(array('/Bar_/','/Hoge_/'));
        S2ContainerApplicationContext::addExcludePattern(array('/Hoge_/'));
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 1);
    }

    public function testCreateComponentDef(){
        $cd = S2ContainerApplicationContext::createComponentDef('AnnoTestA_S2ContainerApplicationContextTest');
        $this->assertTrue($cd instanceof S2Container_ComponentDef);
        $this->assertTrue($cd->getComponentName() == '');

        $cd = S2ContainerApplicationContext::createComponentDef('AnnoTestB_S2ContainerApplicationContextTest');
        $this->assertTrue($cd instanceof S2Container_ComponentDef);
        $this->assertEquals($cd->getComponentName(), 'b');
    }

    public function testHasAnnotation(){
        $clazzB = new ReflectionClass('AnnoTestB_S2ContainerApplicationContextTest');
        $ret = S2ContainerApplicationContext::hasAnnotation($clazzB, 
                               S2ContainerApplicationContext::ASPECT_ANNOTATION);
        $this->assertFalse($ret);
        $ret = S2ContainerApplicationContext::hasAnnotation($clazzB, 
                               S2ContainerApplicationContext::COMPONENT_ANNOTATION);
        $this->assertTrue($ret);
        $ret = S2ContainerApplicationContext::hasAnnotation($clazzB->getMethod('b'), 
                               S2ContainerApplicationContext::ASPECT_ANNOTATION);
        $this->assertTrue($ret);
        $ret = S2ContainerApplicationContext::hasAnnotation($clazzB->getMethod('a'), 
                               S2ContainerApplicationContext::BINDING_ANNOTATION);
        $this->assertTrue($ret);
    }

    public function testGetAnnotation(){
        $clazzB = new ReflectionClass('AnnoTestB_S2ContainerApplicationContextTest');
        $ret = S2ContainerApplicationContext::getAnnotation($clazzB, 
                               S2ContainerApplicationContext::ASPECT_ANNOTATION);
        $this->assertEquals($ret, array());

        $clazzB = new ReflectionClass('AnnoTestB_S2ContainerApplicationContextTest');
        $ret = S2ContainerApplicationContext::getAnnotation($clazzB, 
                               S2ContainerApplicationContext::COMPONENT_ANNOTATION);
        $this->assertEquals($ret['name'], 'b');
        $ret = S2ContainerApplicationContext::getAnnotation($clazzB->getMethod('a'),
                               S2ContainerApplicationContext::BINDING_ANNOTATION);
        $this->assertEquals($ret[0], 'abc');
    }

    public function testFormatCommentLine(){
        $comment = ' /** abc  */ ';
        $format = S2ContainerApplicationContext::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc
                      */ ';
        $format = S2ContainerApplicationContext::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc */ ';
        $format = S2ContainerApplicationContext::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

        $comment = ' /**
                      * abc **/ ';
        $format = S2ContainerApplicationContext::formatCommentLine($comment);
        $this->assertEquals(trim($format), 'abc');

    }

    public function testFilter(){
        S2ContainerApplicationContext::setIncludePattern(array());
        S2ContainerApplicationContext::setExcludePattern(array());

        $items = array('A', 'B', 'C');
        $filtered = S2ContainerApplicationContext::filter($items);
        $this->assertEquals($items, $filtered);

        S2ContainerApplicationContext::setIncludePattern('/A/');
        $filtered = S2ContainerApplicationContext::filter($items);
        $this->assertEquals(array('A'), $filtered);

        S2ContainerApplicationContext::setIncludePattern('/A/');
        S2ContainerApplicationContext::addIncludePattern('/B/');
        S2ContainerApplicationContext::setExcludePattern('/B/');
        $filtered = S2ContainerApplicationContext::filter($items);
        $this->assertEquals(array('A'), $filtered);

        S2ContainerApplicationContext::setIncludePattern(array());
        S2ContainerApplicationContext::setExcludePattern('/B/');
        $filtered = S2ContainerApplicationContext::filter($items);
        $this->assertEquals(array('A', 'C'), $filtered);
    }

    public function setUp(){
        print __CLASS__ . "::{$this->getName()}\n";
        $this->sampleDir = dirname(__FILE__) . '/S2ContainerApplicationContext_classes';
    }

    public function tearDown() {
        print "\n";
    }
}

class AnnoTestA_S2ContainerApplicationContextTest {
    public static function setSetA($a){}
    public static function a(){}
}

/**
 * @S2Component('name' => 'b')
 */
class AnnoTestB_S2ContainerApplicationContextTest {

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
?>
