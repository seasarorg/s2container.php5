<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container.factory
 * @author    klove
 */
namespace seasar\container\factory;
class XmlS2ContainerBuilderTest extends \PHPUnit_Framework_TestCase {

    /**
     * \seasar\container\factory\XmlS2ContainerBuilderTest::errorHandler()が処理したエラーの数です。
     * @var integer
     */
    private $errorCount = 0;

    /**
     * エラーハンドラです。<br />
     * \seasar\container\factory\XmlS2ContainerBuilderTest::$errorCountをインクリメントして、
     * エラーの内容を標準出力に出力します。
     * @param integer $errno
     * @param string $errstr
     * @param string $errfile
     * @param integer $errline
     * @param array $errcontext
     * @return boolean
     */
    public function errorHandler($errno, $errstr, $errfile,  $errline, $errcontext) {
        $this->errorCount++;
        print $errstr . PHP_EOL;
        return true;
    }

    public function test01Build() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test01Build.dicon');
        $this->assertTrue($container instanceof \seasar\container\S2Container);
    }

    public function test02Namespace() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test02Namespace.dicon');
        $this->assertTrue($container->getNamespace() === 'a');
    }

    public function test03ContainerMetaDef() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test03ContainerMetaDef.dicon');
        $metaDef = $container->getMetaDef(0);
        $this->assertTrue($metaDef->getValue() === 2007);
        $metaDef = $container->getMetaDefs('a');
        $this->assertTrue($metaDef[0]->getValue() === 2007);
    }

    public function test04ArgDef() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test04ArgDef.dicon');
        $componentDef = $container->getComponentDef('a');
        $argDef = $componentDef->getArgDef(0);
        $this->assertTrue($argDef->getValue() === 2007);
    }

    public function test05PropertyDef() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test05PropertyDef.dicon');
        $componentDef = $container->getComponentDef('a');
        $propertyDef = $componentDef->getPropertyDef('year');
        $this->assertTrue($propertyDef->getValue() === 2007);
    }

    public function test06AspectDef() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test06AspectDef.dicon');
        $componentDef = $container->getComponentDef('a');
        $aspectDef = $componentDef->getAspectDef(0);
        $pointcut = $aspectDef->getPointcut();
        $this->assertTrue($pointcut instanceof \seasar\aop\Pointcut);
        $this->assertTrue($aspectDef->getValue() instanceof \seasar\aop\interceptor\TraceInterceptor);
    }

    public function test07ComponentMetaDef() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test07ComponentMetaDef.dicon');
        $componentDef = $container->getComponentDef('a');
        $metaDef = $componentDef->getMetaDef(0);
        $this->assertTrue($metaDef->getName() === 'year');
        $this->assertTrue($metaDef->getValue() === 2007);
    }

    public function test08ChildComponentDefArg() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test08ChildComponentDefArg.dicon');
        $componentDef = $container->getComponentDef('a');
        $argDef = $componentDef->getArgDef(0);
        $childComponentDef = $argDef->getChildComponentDef();
        $this->assertTrue($childComponentDef->getComponentName() === 'b');
        $this->assertTrue($childComponentDef->getComponentClass()->getName() === 'seasar\container\factory\B_XmlS2ContainerBuilderTest');
    }

    public function test09ChildComponentDefProperty() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test09ChildComponentDefProperty.dicon');
        $componentDef = $container->getComponentDef('a');
        $propertyDef = $componentDef->getPropertyDef('b');
        $childComponentDef = $propertyDef->getChildComponentDef();
        $this->assertTrue($childComponentDef->getComponentName() === 'b');
        $this->assertTrue($childComponentDef->getComponentClass()->getName() === 'seasar\container\factory\B_XmlS2ContainerBuilderTest');
    }

    public function test10ChildComponentDefAspect() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test10ChildComponentDefAspect.dicon');
        $componentDef = $container->getComponentDef('a');
        $aspectDef = $componentDef->getAspectDef(0);
        $childComponentDef = $aspectDef->getChildComponentDef();
        $this->assertTrue($childComponentDef->getComponentName() === 'b');
        $this->assertTrue($childComponentDef->getComponentClass()->getName() === 'seasar\container\factory\B_XmlS2ContainerBuilderTest');
    }

    public function test11FileNotFound() {
        $builder = new XmlS2ContainerBuilder();
        try {
            $container = $builder->build(dirname(__FILE__) . '/xxx');
            $this->fail();
        } catch (\seasar\exception\FileNotFoundException $e) {
            print $e->getMessage() . PHP_EOL;
        }
    }

    public function test12DomValidate() {
        $builder = new XmlS2ContainerBuilder();
        set_error_handler(array($this, "errorHandler"));
        \seasar\container\Config::$DOM_VALIDATE = true;

        try {
            $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test12DomValidate.dicon');
            $this->fail();
        } catch (\seasar\exception\DOMException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (\Exception $e) {
            restore_error_handler();
            throw $e;
        }
        $this->assertTrue($this->errorCount > 0);

        restore_error_handler();
        \seasar\container\Config::$DOM_VALIDATE = false;
    }

    public function test13InitMethod() {
        $builder = new XmlS2ContainerBuilder();
        $container = $builder->build(dirname(__FILE__) . '/XmlS2ContainerBuilderTest_dicon/test13InitMethod.dicon');
        $d = $container->getComponent('d');
        $this->assertEquals($d->a, 100);
        $this->assertEquals($d->b, 200);
        $this->assertEquals($d->c, 300);
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
        $this->errorCount = 0;
    }

    public function tearDown() {
    }
}

class A_XmlS2ContainerBuilderTest{}

class B_XmlS2ContainerBuilderTest{}

class C_XmlS2ContainerBuilderTest{}

class D_XmlS2ContainerBuilderTest{
    public $a = null;
    public $b = null;
    public $c = null;

    public function init() {
        $this->a = 100;
    }

    public function hoge($b) {
        $this->b = $b;
    }
}
