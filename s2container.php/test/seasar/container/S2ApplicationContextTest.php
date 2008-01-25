<?php
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
/**
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar::container;
class S2ApplicationContextTest extends ::PHPUnit_Framework_TestCase {
    public function __construct($name) {
        parent::__construct($name);
    }

    public function testRegisterException(){
        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir . '/XXX');
        $this->assertEquals(S2ApplicationContext::$CLASSES, array());
    }

    public function testRegisterClass(){
        S2ApplicationContext::import($this->sampleDir);
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['sample::aaa::Bar_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['sample::bbb::Hoge_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['sample::ccc::ddd::Huga_S2ApplicationContext']));

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir, array('xxx','yyy'), true);
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['xxx::yyy::Bar_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['xxx::yyy::Hoge_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['xxx::yyy::Huga_S2ApplicationContext']));

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir, array('xxx'));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['xxx::sample::aaa::Bar_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['xxx::sample::bbb::Hoge_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['xxx::sample::ccc::ddd::Huga_S2ApplicationContext']));

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir, array(), true);
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['Bar_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['Hoge_S2ApplicationContext']));
        $this->assertTrue(isset(S2ApplicationContext::$CLASSES['Huga_S2ApplicationContext']));
    }

    public function testRegisterDicon(){
        S2ApplicationContext::import($this->sampleDir . '/dicon/aaa.dicon');
        $dicons = array_keys(S2ApplicationContext::$DICONS);
        $this->assertEquals($dicons, array('aaa.dicon'));
        $this->assertFalse(in_array('ccc.dicon', $dicons));
    }

    public function testRegisterInternal(){
        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::importInternal('/A/B/C/D.php');
        $this->assertEquals(S2ApplicationContext::$CLASSES, array('D' => '/A/B/C/D.php'));

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::importInternal('/A/B/C/D.php', array('B','C'));
        $this->assertEquals(S2ApplicationContext::$CLASSES, array('B::C::D' => '/A/B/C/D.php'));

        S2ApplicationContext::$DICONS = array();
        S2ApplicationContext::importInternal('/A/B/C/D.dicon', array('C'), true);
        $this->assertEquals(S2ApplicationContext::$DICONS, array('D.dicon' => '/A/B/C/D.dicon'));
    }

    public function testCreate(){
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 0);

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir);
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 4);

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir);
        S2ApplicationContext::addIncludePattern('/Bar_/');
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 1);

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir);
        S2ApplicationContext::addIncludePattern(array('/Bar_/','/Hoge_/'));
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 2);

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir);
        S2ApplicationContext::setIncludePattern(array());
        S2ApplicationContext::addExcludePattern('/Hoge_/');
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 3);

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::import($this->sampleDir);
        S2ApplicationContext::addIncludePattern(array('/Bar_/','/Hoge_/'));
        S2ApplicationContext::addExcludePattern(array('/Hoge_/'));
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->getComponentDefSize() == 1);

        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::setIncludePattern();
        S2ApplicationContext::setExcludePattern();
        S2ApplicationContext::import($this->sampleDir);
        $container = S2ApplicationContext::create();
        $this->assertTrue($container instanceof S2Container);
        $this->assertTrue($container->hasComponentDef('sample::aaa::Foo_S2ApplicationContext'));
        $cd = $container->getComponentDef('sample::aaa::Foo_S2ApplicationContext');
        $c = $cd->getMetaDefSize();
        $this->assertEquals($c, 3);
        $this->assertEquals($cd->getMetaDef('name')->getValue(), 'xyz');
        $this->assertEquals($cd->getMetaDef('year')->getValue(), 2007);
        $this->assertEquals($cd->getMetaDef('add')->getValue(), 5);
    }

    public function testCreateComponentDef(){
        $cd = S2ApplicationContext::createComponentDef(__NAMESPACE__ . '::AnnoTestA_S2ApplicationContextTest');
        $this->assertTrue($cd instanceof ComponentDef);
        $this->assertTrue($cd->getComponentName() == '');

        $cd = S2ApplicationContext::createComponentDef(__NAMESPACE__ . '::AnnoTestB_S2ApplicationContextTest');
        $this->assertTrue($cd instanceof ComponentDef);
        $this->assertEquals($cd->getComponentName(), 'b');
    }

    public function testFilter(){
        S2ApplicationContext::setIncludePattern(array());
        S2ApplicationContext::setExcludePattern(array());

        $items = array('A', 'B', 'C');
        $filtered = S2ApplicationContext::filter($items);
        $this->assertEquals($items, $filtered);

        S2ApplicationContext::setIncludePattern('/A/');
        $filtered = S2ApplicationContext::filter($items);
        $this->assertEquals(array('A'), $filtered);

        S2ApplicationContext::setIncludePattern('/A/');
        S2ApplicationContext::addIncludePattern('/B/');
        S2ApplicationContext::setExcludePattern('/B/');
        $filtered = S2ApplicationContext::filter($items);
        $this->assertEquals(array('A'), $filtered);

        S2ApplicationContext::setIncludePattern(array());
        S2ApplicationContext::setExcludePattern('/B/');
        $filtered = S2ApplicationContext::filter($items);
        $this->assertEquals(array('A', 'C'), $filtered);
    }

    public function testEnvFilter(){
        $items = array('A', 'B', 'C');
        $filtered = S2ApplicationContext::envFilter($items);
        $this->assertEquals($items, $filtered);
        
        seasar::container::Config::$ENVIRONMENT = 'mock';
        $items = array('MockA', 'A', 'B', 'C');
        $filtered = S2ApplicationContext::envFilter($items);
        $this->assertEquals(array('MockA', 'B', 'C'), $filtered);

        S2ApplicationContext::setEnvPrefix('test');
        $items = array('TestA', 'A', 'MockB', 'B', 'C');
        $filtered = S2ApplicationContext::envFilter($items);
        $this->assertEquals(array('TestA', 'MockB', 'B', 'C'), $filtered);

        S2ApplicationContext::setFilterByEnv(false);
        $filtered = S2ApplicationContext::envFilter($items);
        $this->assertEquals(array('TestA', 'A', 'MockB', 'B', 'C'), $filtered);

        seasar::container::Config::$ENVIRONMENT = null;
    }

    public function testReadParentAnnotation(){
        S2ApplicationContext::init();
        S2ApplicationContext::$CLASSES[__NAMESPACE__ . '::AnnoTestD_S2ApplicationContextTest'] = '';
        $container = S2ApplicationContext::create();
        $d = $container->getComponent('d');
        $this->assertTrue($d instanceof seasar::container::AnnoTestD_S2ApplicationContextTest);

        S2ApplicationContext::setReadParentAnnotation();
        $container = S2ApplicationContext::create();
        $d = $container->getComponent('d');
        $this->assertTrue($d instanceof seasar::container::AnnoTestD_S2ApplicationContextTest_EnhancedByS2AOP);
        S2ApplicationContext::setReadParentAnnotation(false);
    }

    public function testRegisterAspect(){
        S2ApplicationContext::init();
        S2ApplicationContext::$CLASSES[__NAMESPACE__ . '::AnnoTestE_S2ApplicationContextTest'] = '';
        S2ApplicationContext::registerAspect('/AnnoTestE_/', 'new seasar::aop::interceptor::TraceInterceptor', 'hoge');
        $container = S2ApplicationContext::create();
        $e = $container->getComponent(__NAMESPACE__ . '::AnnoTestE_S2ApplicationContextTest');
        $this->assertTrue($e instanceof seasar::container::AnnoTestE_S2ApplicationContextTest_EnhancedByS2AOP);

        S2ApplicationContext::$CLASSES[__NAMESPACE__ . '::AnnoTestE_S2ApplicationContextTest'] = '';
        S2ApplicationContext::$CLASSES[__NAMESPACE__ . '::AnnoTestF_S2ApplicationContextTest'] = '';
        S2ApplicationContext::registerAspect('/AnnoTestE_/', 'new seasar::aop::interceptor::TraceInterceptor', 'hoge');
        $container = S2ApplicationContext::create();
        $e = $container->getComponent(__NAMESPACE__ . '::AnnoTestE_S2ApplicationContextTest');
        $f = $container->getComponent('annoTestF');
        $this->assertTrue($e instanceof seasar::container::AnnoTestE_S2ApplicationContextTest_EnhancedByS2AOP);
        $this->assertTrue($f instanceof seasar::container::AnnoTestF_S2ApplicationContextTest);

        S2ApplicationContext::init();
        S2ApplicationContext::$CLASSES[__NAMESPACE__ . '::AnnoTestE_S2ApplicationContextTest'] = '';
        S2ApplicationContext::$CLASSES[__NAMESPACE__ . '::AnnoTestF_S2ApplicationContextTest'] = '';
        S2ApplicationContext::registerAspect('/AnnoTestE_/', 'new seasar::aop::interceptor::TraceInterceptor', 'hoge');
        S2ApplicationContext::registerAspect('/annoTestF/', 'new seasar::aop::interceptor::TraceInterceptor');
        $container = S2ApplicationContext::create();
        $e = $container->getComponent(__NAMESPACE__ . '::AnnoTestE_S2ApplicationContextTest');
        $f = $container->getComponent('annoTestF');
        $this->assertTrue($e instanceof seasar::container::AnnoTestE_S2ApplicationContextTest_EnhancedByS2AOP);
        $this->assertTrue($f instanceof seasar::container::AnnoTestF_S2ApplicationContextTest_EnhancedByS2AOP);
        $f->hoge();
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
        $this->sampleDir = dirname(__FILE__) . '/S2ApplicationContext_classes';
        S2ApplicationContext::$CLASSES = array();
        S2ApplicationContext::$DICONS  = array();
    }

    public function tearDown() {
    }

}

class AnnoTestA_S2ApplicationContextTest {
    public static function setSetA($a){}
    public static function a(){}
}

/**
 * @S2Component('name' => 'b')
 */
class AnnoTestB_S2ApplicationContextTest {

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

class AnnoTestC_S2ApplicationContextTest {
    /**
     * @S2Aspect('interceptor' => 'new seasar::aop::interceptor::TraceInterceptor')
     */
    public function hoge(){}
}

/**
 * @S2Component('name' => 'd')
 */
class AnnoTestD_S2ApplicationContextTest extends AnnoTestC_S2ApplicationContextTest{}

class AnnoTestE_S2ApplicationContextTest {
    public function hoge(){}
}

interface IAnnoTestF_S2ApplicationContextTest{
    public function hoge();
}

/**
 * @S2Component('name' => 'annoTestF')
 */
class AnnoTestF_S2ApplicationContextTest implements IAnnoTestF_S2ApplicationContextTest {
    public function hoge(){}
}
