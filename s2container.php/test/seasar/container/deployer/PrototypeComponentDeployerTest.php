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
 * @package   seasar.container.deployer
 * @author    klove
 */
namespace seasar\container\deployer;
class PrototypeComponentDeployerTest extends \PHPUnit_Framework_TestCase {

    public function testAssembleWithoutArgs() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_PrototypeComponentDeployerTest');
        $componentDef->setInstanceDef(InstanceDefFactory::getInstanceDef(\seasar\container\InstanceDef::PROTOTYPE_NAME));
        $deployer = new PrototypeComponentDeployer($componentDef);
        $this->assertTrue($deployer->deploy() instanceof \seasar\container\deployer\A_PrototypeComponentDeployerTest);
    }

    public function testPrototype() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\A_PrototypeComponentDeployerTest');
        $componentDef->setInstanceDef(InstanceDefFactory::getInstanceDef(\seasar\container\InstanceDef::PROTOTYPE_NAME));
        $deployer = new PrototypeComponentDeployer($componentDef);
        $this->assertTrue($deployer->deploy() !== $deployer->deploy());
    }

    public function testCyclicInstantiation1Step() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\B_PrototypeComponentDeployerTest');
        $componentDef->setInstanceDef(InstanceDefFactory::getInstanceDef(\seasar\container\InstanceDef::PROTOTYPE_NAME));
        $container->register($componentDef);
        $deployer = new PrototypeComponentDeployer($componentDef);
        try {
            $deployer->deploy();
            $this->fail();
        } catch(\seasar\container\exception\CyclicInstantiationRuntimeException $e) {
            print $e->getMessage() .PHP_EOL;
        } catch(Exception $e) {
            print $e->getMessage() .PHP_EOL;
            $this->fail();
        }
    }

    public function testCyclicInstantiation2Step() {
        $container = new \seasar\container\impl\S2ContainerImpl();
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\C_PrototypeComponentDeployerTest');
        $componentDef->setInstanceDef(InstanceDefFactory::getInstanceDef(\seasar\container\InstanceDef::PROTOTYPE_NAME));
        $container->register($componentDef);
        $componentDef = new \seasar\container\impl\ComponentDefImpl(__NAMESPACE__ . '\D_PrototypeComponentDeployerTest');
        $container->register($componentDef);
        $deployer = new PrototypeComponentDeployer($componentDef);
        try {
            $deployer->deploy();
            $this->fail();
        } catch(\seasar\container\exception\CyclicInstantiationRuntimeException $e) {
            print $e->getMessage() .PHP_EOL;
        } catch(Exception $e) {
            print $e->getMessage() .PHP_EOL;
            $this->fail();
        }
    }

    public function setUp(){
        print PHP_EOL . __CLASS__ . '->' . $this->getName() . '()' . PHP_EOL;
    }

    public function tearDown() {
    }
}

class A_PrototypeComponentDeployerTest {}

class B_PrototypeComponentDeployerTest {
    public function __construct(B_PrototypeComponentDeployerTest $b) {
    }
}

class C_PrototypeComponentDeployerTest {
    public function __construct(D_PrototypeComponentDeployerTest $b) {
    }
}

class D_PrototypeComponentDeployerTest {
    public function __construct(C_PrototypeComponentDeployerTest $b) {
    }
}
