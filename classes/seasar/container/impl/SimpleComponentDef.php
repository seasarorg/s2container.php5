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
 * ComponentDefのシンプルな実装です。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container\impl;
class SimpleComponentDef implements \seasar\container\ComponentDef {

    /**
     * @var object
     */
    private $component;

    /**
     * @var \ReflectionClass
     */
    private $componentClass;

    /**
     * @var string
     */
    private $componentClassName;

    /**
     * @var string
     */
    private $componentName;

    /**
     * @var \seasar\container\S2Container
     */
    private $container;

    /**
     * SimpleComponentDef を構築します。
     *
     * @param object $component
     * @param string $componentName
     */
    public function __construct($component, $componentName = '') {
        $this->component = $component;
        $this->componentClass = new \ReflectionClass($component);
        $this->componentClassName = $this->componentClass->getName();
        $this->componentName = $componentName;
    }

    /**
     * @see \seasar\container\ComponentDef::getComponent()
     */
    public function getComponent() {
        return $this->component;
    }

    /**
     * @see \seasar\container\ComponentDef::getContainer()
     */
    public function getContainer() {
        return $this->container;
    }

    /**
     * @see \seasar\container\ComponentDef::setContainer()
     */
    public function setContainer(\seasar\container\S2Container $container) {
        $this->container = $container;
    }

    /**
     * @see \seasar\container\ComponentDef::getComponentClass()
     */
    public function getComponentClass() {
        return $this->componentClass;
    }

    /**
     * @see \seasar\container\ComponentDef::getComponentName()
     */
    public function getComponentName() {
        return $this->componentName;
    }

    /**
     * @see \seasar\container\ComponentDef::setComponentName()
     */
    public final function setComponentName($name) {
        $this->componentName = $name;
    }

    /**
     * @see \seasar\container\ComponentDef::getConcreteClass()
     */
    public function getConcreteClass() {
        return $this->componentClass;
    }

    /**
     * @see \seasar\container\ComponentDef::addArgDef()
     */
    public function addArgDef(\seasar\container\impl\ArgDef $argDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::addPropertyDef()
     */
    public function addPropertyDef(\seasar\container\impl\PropertyDef $propertyDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::addInitMethodDef()
     */
    public function addInitMethodDef(\seasar\container\impl\InitMethodDef $methodDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::addAspectDef()
     */
    public function addAspectDef(\seasar\container\impl\AspectDef $aspectDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\PropertyDefAware::getPropertyDefSize()
     */
    public function getPropertyDefSize() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\InitMethodDefAware::getInitMethodDefSize()
     */
    public function getInitMethodDefSize() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\AspectDefAware::getAspectDefSize()
     */
    public function getAspectDefSize() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\ArgDefAware::getArgDefs()
     */
    public function getArgDefs() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\PropertyDefAware::getPropertyDefs()
     */
    public function getPropertyDefs() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\InitMethodDefAware::getInitMethodDefs()
     */
    public function getInitMethodDefs() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\AspectDefAware::getAspectDefs()
     */
    public function getAspectDefs() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\ArgDefAware::getArgDef()
     */
    public function getArgDef($index) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\PropertyDefAware::getPropertyDef()
     */
    public function getPropertyDef($index) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\PropertyDefAware::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\InitMethodDefAware::getInitMethodDef()
     */
    public function getInitMethodDef($index) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\AspectDefAware::getAspectDef()
     */
    public function getAspectDef($index) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }
    
    /**
     * @see \seasar\container\util\MetaDefAware::addMetaDef()
     */
    public function addMetaDef(\seasar\container\impl\MetaDef $metaDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }
    
    /**
     * @see \seasar\container\util\MetaDefAware::getMetaDef()
     */
    public function getMetaDef($index) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\util\MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }
    
    /**
     * @see \seasar\container\util\MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::getInstanceDef()
     */
    public function getInstanceDef() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::setInstanceDef()
     */
    public function setInstanceDef(\seasar\container\InstanceDef $instanceDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::getAutoBindingDef()
     */
    public function getAutoBindingDef() {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }

    /**
     * @see \seasar\container\ComponentDef::setAutoBindingDef()
     */
    public function setAutoBindingDef(\seasar\container\AutoBindingDef $autoBindingDef) {
        throw new \seasar\exception\UnsupportedOperationException(__METHOD__);
    }
}
