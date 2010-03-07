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
 * @package   seasar.beans
 * @author    klove
 */
namespace seasar\beans;
class BeanDescTest extends \PHPUnit_Framework_TestCase {

    public function testHasSetterMethod() {
        $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
        $this->assertTrue($bean->hasPropertyDesc('dao1'));
        $this->assertFalse($bean->hasPropertyDesc('dao2'));
        $this->assertTrue($bean->hasPropertyDesc('dao3'));
        $this->assertFalse($bean->hasPropertyDesc('dao4'));
        $this->assertTrue($bean->hasPropertyDesc('dao5'));
    }

    public function testHasTypehintSetterMethod() {
        $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
        $this->assertFalse($bean->hasTypehintPropertyDesc('dao1'));
        $this->assertFalse($bean->hasTypehintPropertyDesc('dao2'));
        $this->assertTrue($bean->hasTypehintPropertyDesc('dao3'));
        $this->assertFalse($bean->hasTypehintPropertyDesc('dao4'));
        $this->assertFalse($bean->hasTypehintPropertyDesc('dao5'));
    }

    public function testGetSetterMethod() {
        $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
        $propertyDesc = $bean->getPropertyDesc('dao1');
        $this->assertTrue($propertyDesc instanceof AccessorMethodPropertyDesc);
        $this->assertTrue($propertyDesc->getWriteMethod() instanceof \ReflectionMethod);
        $this->assertEquals($propertyDesc->getTypehint(), null);
        $this->assertFalse($propertyDesc->isArrayAcceptable());

        $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
        $propertyDesc = $bean->getPropertyDesc('dao5');
        $this->assertTrue($propertyDesc instanceof AccessorMethodPropertyDesc);
        $this->assertTrue($propertyDesc->getWriteMethod() instanceof \ReflectionMethod);
        $this->assertEquals($propertyDesc->getTypehint(), null);
        $this->assertTrue($propertyDesc->isArrayAcceptable());
    }

    public function testGetSetterMethodException() {
        try {
            $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
            $propertyDesc = $bean->getPropertyDesc('xxx');
            $this->fail();
        } catch (\seasar\exception\PropertyNotFoundRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testGetTyphintSetterMethod() {
        $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
        $propertyDesc = $bean->getTypehintPropertyDesc('dao3');
        $this->assertTrue($propertyDesc instanceof AccessorMethodPropertyDesc);
        $this->assertTrue($propertyDesc->getWriteMethod() instanceof \ReflectionMethod);
        $this->assertTrue($propertyDesc->getTypehintClass() instanceof \ReflectionClass);
        $this->assertTrue($propertyDesc->getTypehintClass()->getName() === __NAMESPACE__ . '\B_BeanDescTest');
        $this->assertFalse($propertyDesc->isArrayAcceptable());
    }

    public function testGetTyphintSetterMethodException() {
        try {
            $bean = BeanDescFactory::getBeanDesc('\seasar\beans\A_BeanDescTest');
            $propertyDesc = $bean->getTypehintPropertyDesc('xxx');
            $this->fail();
        } catch (\seasar\exception\PropertyNotFoundRuntimeException $e) {
            print $e->getMessage() . PHP_EOL;
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_BeanDescTest {
    public $name = 'S2Binding \seasar\beans\B_BeanDescTest';
    public $year = 2007;
    public $service = 'S2Binding \seasar\beans\B_BeanDescTest[]';
    private $value = 'aaa';

    public function setDao1($dao) {
    }

    public function setDao2($dao, $entity) {
    }

    public function setDao3(B_BeanDescTest $dao) {
    }

    protected function setDao4(B_BeanDescTest $dao) {
    }

    public function setDao5(array $dao) {
    }

}

class B_BeanDescTest{}

class C_BeanDescTest {
    public $hogeLogic = 'S2Binding';
}

class D_BeanDescTest {
    public $hogeLogic = null;
    public $hugaLogic = '';
    public $barLogic = '';
}
class E_BeanDescTest {
    public $hogeLogic;
}
