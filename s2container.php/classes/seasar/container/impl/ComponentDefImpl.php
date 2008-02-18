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
 * ComponentDefの実装クラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container.impl
 * @author    klove
 */
namespace seasar::container::impl;
class ComponentDefImpl implements seasar::container::ComponentDef {

    /**
     * @var ReflectionClass
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
     * @var seasar::container::S2Container
     */
    private $container = null;

    /**
     * @var seasar::container::util::ArgDefSupport
     */
    private $argDefSupport = null;

    /**
     * @var seasar::container::util::PropertyDefSupport
     */
    private $propertyDefSupport = null;

    /**
     * @var seasar::container::util::MethodDefSupport
     */
    private $initMethodDefSupport = null;

    /**
     * @var seasar::container::util::AspectDefSupport
     */
    private $aspectDefSupport = null;

    /**
     * @var seasar::container::util::MetaDefSupport
     */
    private $metaDefSupport = null;

    /**
     * @var seasar::container::InstanceDef
     */
    private $instanceDef = null;

    /**
     * @var seasar::container::AutoBindingDef
     */
    private $autoBindingDef = null;

    /**
     * @var seasar::container::deployer::AbstractComponentDeployer
     */
    private $componentDeployer = null;

    /**
     * ComponentDefImplを構築します。
     *
     * @param string component class name
     * @param string component name
     */
    public function __construct($componentClassName, $componentName = null) {
        if ($componentClassName instanceof ReflectionClass) {
            $componentClassName = $componentClassName->getName();
        }
        $this->componentClass       = new ReflectionClass($componentClassName);
        $this->componentName        = $componentName;
        $this->argDefSupport        = new seasar::container::util::ArgDefSupport();
        $this->propertyDefSupport   = new seasar::container::util::PropertyDefSupport();
        $this->initMethodDefSupport = new seasar::container::util::InitMethodDefSupport();
        $this->aspectDefSupport     = new seasar::container::util::AspectDefSupport();
        $this->metaDefSupport       = new seasar::container::util::MetaDefSupport();
        $this->instanceDef          = seasar::container::deployer::InstanceDefFactory::getInstanceDef(seasar::container::InstanceDef::SINGLETON_NAME);
        $this->autoBindingDef       = seasar::container::assembler::AutoBindingDefFactory::getAutoBindingDef(seasar::container::AutoBindingDef::AUTO_NAME);
    }

    /**
     * @see seasar::container::ComponentDef::getComponent()
     */
    public function getComponent() {
        return $this->getComponentDeployer()->deploy();
    }

    /**
     * @see seasar::container::ComponentDef::getComponentClass()
     */
    public final function getComponentClass() {
        return $this->componentClass;
    }

    /**
     * @see seasar::container::ComponentDef::getComponentName()
     */
    public final function getComponentName() {
        return $this->componentName;
    }

    /**
     * @see seasar::container::ComponentDef::setComponentName()
     */
    public final function setComponentName($name) {
        $this->componentName = $name;
    }

    /**
     * @see seasar::container::ComponentDef::getContainer()
     */
    public final function getContainer() {
        return $this->container;
    }

    /**
     * @see seasar::container::ComponentDef::setContainer()
     */
    public final function setContainer(seasar::container::S2Container $container) {
        $this->container = $container;
        $this->argDefSupport->setContainer($container);
        $this->metaDefSupport->setContainer($container);
        $this->propertyDefSupport->setContainer($container);
        $this->initMethodDefSupport->setContainer($container);
        $this->aspectDefSupport->setContainer($container);
    }

    /**
     * @see seasar::container::ComponentDef::getInstanceDef()
     */
    public function getInstanceDef() {
        return $this->instanceDef;
    }

    /**
     * @see seasar::container::ComponentDef::setInstanceDef()
     */
    public function setInstanceDef(seasar::container::InstanceDef $instanceDef) {
        $this->instanceDef = $instanceDef;
    }

    /**
     * @see seasar::container::ComponentDef::getAutoBindingDef()
     */
    public function getAutoBindingDef() {
        return $this->autoBindingDef;
    }

    /**
     * @see seasar::container::ComponentDef::setAutoBindingDef()
     */
    public function setAutoBindingDef(seasar::container::AutoBindingDef $autoBindingDef) {
        $this->autoBindingDef = $autoBindingDef;
    }

    /**
     * @see seasar::container::util::ArgDefSupport::getArgDef()
     */
    public function getArgDef($index) {
        return $this->argDefSupport->getArgDef($index);
    }

    /**
     * @see seasar::container::util::ArgDefSupport::getArgDefSize()
     */
    public function getArgDefSize() {
        return $this->argDefSupport->getArgDefSize();
    }

    /**
     * @see seasar::container::util::ArgDefSupport::addArgDef()
     */
    public function addArgDef(seasar::container::impl::ArgDef $argDef) {
        $this->argDefSupport->addArgDef($argDef);
    }

    /**
     * @see seasar::container::util::PropertyDefSupport::addPropertyDef()
     */
    public function addPropertyDef(seasar::container::impl::PropertyDef $propertyDef) {
        $this->propertyDefSupport->addPropertyDef($propertyDef);
    }

    /**
     * @see seasar::container::util::PropertyDefSupport::getPropertyDef()
     */
    public function getPropertyDef($index) {
        return $this->propertyDefSupport->getPropertyDef($index);
    }

    /**
     * @see seasar::container::util::PropertyDefSupport::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName) {
        return $this->propertyDefSupport->hasPropertyDef($propertyName);
    }

    /**
     * @see seasar::container::util::PropertyDefSupport::getPropertyDefSize()
     */
    public function getPropertyDefSize() {
        return $this->propertyDefSupport->getPropertyDefSize();
    }

    /**
     * @see seasar::container::util::InitMethodDefSupport::getInitMethodDef()
     */
    public function getInitMethodDef($index) {
        return $this->initMethodDefSupport->getInitMethodDef($index);
    }

    /**
     * @see seasar::container::util::InitMethodDefSupport::getInitMethodDefSize()
     */
    public function getInitMethodDefSize() {
        return $this->initMethodDefSupport->getInitMethodDefSize();
    }

    /**
     * @see seasar::container::util::InitMethodDefSupport::addInitMethodDef()
     */
    public function addInitMethodDef(seasar::container::impl::InitMethodDef $methodDef) {
        $this->initMethodDefSupport->addInitMethodDef($methodDef);
    }

    /**
     * @see seasar::container::util::AspectDefSupport::getAspectDef()
     */
    public function getAspectDef($index) {
        return $this->aspectDefSupport->getAspectDef($index);
    }

    /**
     * @see seasar::container::util::AspectDefSupport::getAspectDefSize()
     */
    public function getAspectDefSize() {
        return $this->aspectDefSupport->getAspectDefSize();
    }

    /**
     * @see seasar::container::util::AspectDefSupport::addAspectDef()
     */
    public function addAspectDef(seasar::container::impl::AspectDef $aspectDef) {
        $this->aspectDefSupport->addAspectDef($aspectDef);
    }

    /**
     * @see seasar::container::util::MetaDefSupport::addMetaDef()
     */
    public function addMetaDef(seasar::container::impl::MetaDef $metaDef) {
        $this->metaDefSupport->addMetaDef($metaDef);
    }

    /**
     * @see seasar::container::util::MetaDefSupport::getMetaDef()
     */
    public function getMetaDef($index) {
        return $this->metaDefSupport->getMetaDef($index);
    }

    /**
     * @see seasar::container::util::MetaDefSupport::getMetaDefs()
     */
    public function getMetaDefs($name) {
        return $this->metaDefSupport->getMetaDefs($name);
    }

    /**
     * @see seasar::container::util::MetaDefSupport::getMetaDefSize()
     */
    public function getMetaDefSize() {
        return $this->metaDefSupport->getMetaDefSize();
    }

    /**
     * AbstractComponentDeployerを返します。
     *
     * @return seasar::container::deployer::AbstractComponentDeployer
     */
    private function getComponentDeployer() {
        if ($this->componentDeployer == null) {
            $this->componentDeployer = $this->instanceDef->createComponentDeployer($this);
        }
        return $this->componentDeployer;
    }
}
