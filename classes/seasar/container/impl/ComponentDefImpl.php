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
 * ComponentDefの実装クラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar\container\impl;
class ComponentDefImpl implements \seasar\container\ComponentDef {

    /**
     * @var \ReflectionClass
     */
    private $componentClass = null;

    /**
     * @var string
     */
    private $componentClassName = null;

    /**
     * @var string
     */
    private $componentName = null;

    /**
     * @var \seasar\container\S2Container
     */
    private $container = null;

    /**
     * @var \seasar\container\util\PropertyDefSupport
     */
    private $propertyDefs = array();

    /**
     * @var \seasar\container\util\AspectDefSupport
     */
    private $aspectDefs = array();

    /**
     * @var \seasar\container\InstanceDef
     */
    private $instanceDef = null;

    /**
     * @var \seasar\container\AutoBindingDef
     */
    private $autoBindingDef = null;

    /**
     * @var \seasar\container\deployer\AbstractComponentDeployer
     */
    private $componentDeployer = null;

    /**
     * ComponentDefImplを構築します。
     *
     * @param string component class name
     * @param string component name
     */
    public function __construct($componentClassName, $componentName = null) {
        if ($componentClassName instanceof \ReflectionClass) {
            $componentClassName = $componentClassName->getName();
        }
        $this->componentClass       = new \ReflectionClass($componentClassName);
        $this->componentName        = $componentName;
        $this->instanceDef          = \seasar\container\deployer\InstanceDefFactory::getInstanceDef(\seasar\container\InstanceDef::SINGLETON_NAME);
        $this->autoBindingDef       = \seasar\container\assembler\AutoBindingDefFactory::getAutoBindingDef(\seasar\container\AutoBindingDef::AUTO_NAME);
    }

    /**
     * @see \seasar\container\ComponentDef::getComponent()
     */
    public function getComponent() {
        return $this->getComponentDeployer()->deploy();
    }

    /**
     * @see \seasar\container\ComponentDef::getComponentClass()
     */
    public final function getComponentClass() {
        return $this->componentClass;
    }

    /**
     * @see \seasar\container\ComponentDef::getComponentName()
     */
    public final function getComponentName() {
        return $this->componentName;
    }

    /**
     * @see \seasar\container\ComponentDef::setComponentName()
     */
    public final function setComponentName($name) {
        $this->componentName = $name;
    }

    /**
     * @see \seasar\container\ComponentDef::getContainer()
     */
    public final function getContainer() {
        return $this->container;
    }

    /**
     * @see \seasar\container\ComponentDef::setContainer()
     */
    public final function setContainer(\seasar\container\S2Container $container) {
        $this->container = $container;
        foreach ($this->propertyDefs as $propertyName => $propertDef) {
            $propertyDef->setContainer($container);
        }
        foreach ($this->aspectDefs as $aspectDef) {
            $aspectDef->setContainer($container);
        }
    }

    /**
     * @see \seasar\container\ComponentDef::getInstanceDef()
     */
    public function getInstanceDef() {
        return $this->instanceDef;
    }

    /**
     * @see \seasar\container\ComponentDef::setInstanceDef()
     */
    public function setInstanceDef(\seasar\container\InstanceDef $instanceDef) {
        $this->instanceDef = $instanceDef;
    }

    /**
     * @see \seasar\container\ComponentDef::getAutoBindingDef()
     */
    public function getAutoBindingDef() {
        return $this->autoBindingDef;
    }

    /**
     * @see \seasar\container\ComponentDef::setAutoBindingDef()
     */
    public function setAutoBindingDef(\seasar\container\AutoBindingDef $autoBindingDef) {
        $this->autoBindingDef = $autoBindingDef;
    }

    /**
     * @see \seasar\container\util\PropertyDefSupport::getPropertyDefs()
     */
    public function getPropertyDefs() {
        return $this->propertyDefs;
    }

    /**
     * @see \seasar\container\util\PropertyDefSupport::hasPropertyDef()
     */
    public function getPropertyDef($propertyName) {
        if($this->hasPropertyDef($propertyName)) {
            return $this->propertyDefs[$propertyName];
        }
        throw new \OutOfRangeException($propertyName);
    }

    /**
     * @see \seasar\container\util\PropertyDefSupport::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName) {
        return array_key_exists($propertyName, $this->propertyDefs);
    }

    /**
     * @see \seasar\container\util\PropertyDefSupport::addPropertyDef()
     */
    public function addPropertyDef(\seasar\container\impl\PropertyDef $propertyDef) {
        if (!is_null($this->container)) {
            $propertyDef->setContainer($this->container);
        }
        $this->propertyDefs[$propertyDef->getPropertyName()] = $propertyDef;
    }

    /**
     * @see \seasar\container\util\PropertyDefSupport::getPropertyDefSize()
     */
    public function getPropertyDefSize() {
        return count($this->propertyDefs);
    }

    /**
     * @see \seasar\container\util\AspectDefSupport::getAspectDefs()
     */
    public function getAspectDefs() {
        return $this->aspectDefs;
    }

    /**
     * @see \seasar\container\util\AspectDefSupport::getAspectDef()
     */
    public function getAspectDef($index) {
        if (isset($this->aspectDefs[$index])) {
            return $this->aspectDefs[$index];
        }
        throw new \OutOfRangeException($propertyName);
    }

    /**
     * @see \seasar\container\util\AspectDefSupport::getAspectDefSize()
     */
    public function getAspectDefSize() {
        return count($this->aspectDefs);
    }

    /**
     * @see \seasar\container\util\AspectDefSupport::addAspectDef()
     */
    public function addAspectDef(\seasar\container\impl\AspectDef $aspectDef) {
        if (!is_null($this->container)) {
            $aspectDef->setContainer($this->container);
        }
        $this->aspectDefs[] = $aspectDef;
    }

    /**
     * AbstractComponentDeployerを返します。
     *
     * @return \seasar\container\deployer\AbstractComponentDeployer
     */
    private function getComponentDeployer() {
        if ($this->componentDeployer == null) {
            $this->componentDeployer = $this->instanceDef->createComponentDeployer($this);
        }
        return $this->componentDeployer;
    }
}
