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
class S2ContainerApplicationContextTest extends PHPUnit_Framework_TestCase {
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
        $this->assertFalse(in_array('ccc.dicon', $dicons));

        S2ContainerApplicationContext::$DICONS = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/dicon', 1);
        $dicons = array_keys(S2ContainerApplicationContext::$DICONS);
        $this->assertEquals($dicons, array('aaa.dicon','ccc.dicon'));

        S2ContainerApplicationContext::$DICONS = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/dicon', -1);
        $dicons = array_keys(S2ContainerApplicationContext::$DICONS);
        $this->assertEquals($dicons, array('aaa.dicon','ccc.dicon'));
    }

    public function testImportPear(){
        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', -1, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Www_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_XImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_Zzz_ZImpl_S2ContainerApplicationContext', $classes));

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', 0, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Www_S2ContainerApplicationContext', $classes));
        $this->assertFalse(in_array('Xxx_XImpl_S2ContainerApplicationContext', $classes));
        $this->assertFalse(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
        $this->assertFalse(in_array('Xxx_Yyy_Zzz_ZImpl_S2ContainerApplicationContext', $classes));

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', 1, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Www_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_XImpl_S2ContainerApplicationContext', $classes));
        $this->assertFalse(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
        $this->assertFalse(in_array('Xxx_Yyy_Zzz_ZImpl_S2ContainerApplicationContext', $classes));

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', 2, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Www_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_XImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
        $this->assertFalse(in_array('Xxx_Yyy_Zzz_ZImpl_S2ContainerApplicationContext', $classes));

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', 3, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Www_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_XImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_Zzz_ZImpl_S2ContainerApplicationContext', $classes));

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::import($this->sampleDir . '/pear', 4, true);
        $classes = array_keys(S2ContainerApplicationContext::$CLASSES);
        $this->assertTrue(in_array('Www_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_XImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_YImpl_S2ContainerApplicationContext', $classes));
        $this->assertTrue(in_array('Xxx_Yyy_Zzz_ZImpl_S2ContainerApplicationContext', $classes));
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

        S2ContainerApplicationContext::$CLASSES = array();
        S2ContainerApplicationContext::setIncludePattern();
        S2ContainerApplicationContext::setExcludePattern();
        S2ContainerApplicationContext::import($this->sampleDir . '/sample', true);
        $container = S2ContainerApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->hasComponentDef('Foo_S2ContainerApplicationContext'));
        $cd = $container->getComponentDef('Foo_S2ContainerApplicationContext');
        $c = $cd->getMetaDefSize();
        $this->assertEquals($c, 3);
        $this->assertEquals($cd->getMetaDef('name')->getValue(), 'xyz');
        $this->assertEquals($cd->getMetaDef('year')->getValue(), 2007);
        $this->assertEquals($cd->getMetaDef('add')->getValue(), 5);
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

    public function testEnvFilter(){
        $items = array('A', 'B', 'C');
        $filtered = S2ContainerApplicationContext::envFilter($items);
        $this->assertEquals($items, $filtered);
        
        if (!defined('S2CONTAINER_PHP5_ENV')) {define('S2CONTAINER_PHP5_ENV', 'mock');}
        $items = array('MockA', 'A', 'B', 'C');
        $filtered = S2ContainerApplicationContext::envFilter($items);
        $this->assertEquals(array('MockA', 'B', 'C'), $filtered);

        S2ContainerApplicationContext::setEnvPrefix('test');
        $items = array('TestA', 'A', 'MockB', 'B', 'C');
        $filtered = S2ContainerApplicationContext::envFilter($items);
        $this->assertEquals(array('TestA', 'MockB', 'B', 'C'), $filtered);

        S2ContainerApplicationContext::setFilterByEnv(false);
        $filtered = S2ContainerApplicationContext::envFilter($items);
        $this->assertEquals(array('TestA', 'A', 'MockB', 'B', 'C'), $filtered);
    }

    public function testReadParentAnnotation(){
        S2ContainerApplicationContext::init();
        S2ContainerApplicationContext::$CLASSES['AnnoTestD_S2ContainerApplicationContextTest'] = '';
        $container = S2ContainerApplicationContext::create();
        $d = $container->getComponent('d');
        $this->assertType('AnnoTestD_S2ContainerApplicationContextTest', $d);

        S2ContainerApplicationContext::setReadParentAnnotation();
        $container = S2ContainerApplicationContext::create();
        $d = $container->getComponent('d');
        $this->assertType('AnnoTestD_S2ContainerApplicationContextTest_EnhancedByS2AOP', $d);
        S2ContainerApplicationContext::setReadParentAnnotation(false);
    }

    public function testRegisterAnnotation(){
        S2ContainerApplicationContext::init();
        S2ContainerApplicationContext::$CLASSES['AnnoTestE_S2ContainerApplicationContextTest'] = '';
        S2ContainerApplicationContext::registerAspect('/AnnoTestE_/', 'new S2Container_TraceInterceptor', 'hoge');
        $container = S2ContainerApplicationContext::create();
        $e = $container->getComponent('AnnoTestE_S2ContainerApplicationContextTest');
        $this->assertType('AnnoTestE_S2ContainerApplicationContextTest_EnhancedByS2AOP', $e);

        S2ContainerApplicationContext::$CLASSES['AnnoTestE_S2ContainerApplicationContextTest'] = '';
        S2ContainerApplicationContext::$CLASSES['AnnoTestF_S2ContainerApplicationContextTest'] = '';
        S2ContainerApplicationContext::registerAspect('/AnnoTestE_/', 'new S2Container_TraceInterceptor', 'hoge');
        $container = S2ContainerApplicationContext::create();
        $e = $container->getComponent('AnnoTestE_S2ContainerApplicationContextTest');
        $f = $container->getComponent('annoTestF');
        $this->assertType('AnnoTestE_S2ContainerApplicationContextTest_EnhancedByS2AOP', $e);
        $this->assertType('AnnoTestF_S2ContainerApplicationContextTest', $f);

        S2ContainerApplicationContext::init();
        S2ContainerApplicationContext::$CLASSES['AnnoTestE_S2ContainerApplicationContextTest'] = '';
        S2ContainerApplicationContext::$CLASSES['AnnoTestF_S2ContainerApplicationContextTest'] = '';
        S2ContainerApplicationContext::registerAspect('/AnnoTestE_/', 'new S2Container_TraceInterceptor', 'hoge');
        S2ContainerApplicationContext::registerAspect('/annoTestF/', 'new S2Container_TraceInterceptor');
        $container = S2ContainerApplicationContext::create();
        $e = $container->getComponent('AnnoTestE_S2ContainerApplicationContextTest');
        $f = $container->getComponent('annoTestF');
        $this->assertType('AnnoTestE_S2ContainerApplicationContextTest_EnhancedByS2AOP', $e);
        $this->assertType('AnnoTestF_S2ContainerApplicationContextTest_EnhancedByS2AOP', $f);
        $f->hoge();
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

class AnnoTestC_S2ContainerApplicationContextTest {
    /**
     * @S2Aspect('interceptor' => 'new S2Container_TraceInterceptor')
     */
    public function hoge(){}
}

/**
 * @S2Component('name' => 'd')
 */
class AnnoTestD_S2ContainerApplicationContextTest extends AnnoTestC_S2ContainerApplicationContextTest{}

class AnnoTestE_S2ContainerApplicationContextTest {
    public function hoge(){}
}

interface IAnnoTestF_S2ContainerApplicationContextTest{
    public function hoge();
}

/**
 * @S2Component('name' => 'annoTestF')
 */
class AnnoTestF_S2ContainerApplicationContextTest implements IAnnoTestF_S2ContainerApplicationContextTest {
    public function hoge(){}
}

?>
