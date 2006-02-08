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
// $Id$
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2Container_SimpleComponentDef 
    implements S2Container_ComponentDef
{
    private $component_;
    private $componentClass_;
    private $componentClassName_;
    private $componentName_;
    private $container_;

    /**
     * @param object $component
     * @param string $componentName
     */
    public function __construct($component,$componentName = "")
    {
        $this->component_ = $component;
        $this->componentClass_ = new ReflectionClass($component);
        $this->componentClassName_ = $this->componentClass_->getName();
        $this->componentName_ = $componentName;
    }

    /**
     * @see S2Container_ComponentDef::getComponent()
     */
    public function getComponent()
    {
        return $this->component_;
    }
    
    /**
     * @see S2Container_ComponentDef::injectDependency()
     */
    public function injectDependency($outerComponent)
    {
        throw new S2Container_UnsupportedOperationException("injectDependency");
    }

    /**
     * @see S2Container_ComponentDef::getContainer()
     */
    public function getContainer()
    {
        return $this->container_;
    }

    /**
     * @see S2Container_ComponentDef::setContainer()
     */
    public function setContainer(S2Container $container)
    {
        $this->container_ = $container;
    }

    /**
     * @see S2Container_ComponentDef::getComponentClass()
     */
    public function getComponentClass()
    {
        return $this->componentClass_;
    }

    /**
     * @see S2Container_ComponentDef::getComponentName()
     */
    public function getComponentName()
    {
        return $this->componentName_;
    }

    /**
     * @see S2Container_ComponentDef::setComponentName()
     */
    public final function setComponentName($name)
    {
        $this->componentName_ = $name;
    }

    /**
     * @see S2Container_ComponentDef::getConcreteClass()
     */
    public function getConcreteClass()
    {
        return $this->componentClass_;
    }

    /**
     * @see S2Container_ComponentDef::addArgDef()
     */
    public function addArgDef(S2Container_ArgDef $constructorArgDef)
    {
        throw new S2Container_UnsupportedOperationException("addArgDef");
    }

    /**
     * @see S2Container_ComponentDef::addPropertyDef()
     */
    public function addPropertyDef(S2Container_PropertyDef $propertyDef)
    {
        throw new S2Container_UnsupportedOperationException("addPropertyDef");
    }

    /**
     * @see S2Container_InitMethodDefAware::addInitMethodDef()
     */
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef)
    {
        throw new S2Container_UnsupportedOperationException("addInitMethodDef");
    }
    
    /**
     * @see S2Container_DestroyMethodDefAware::addDestroyMethodDef()
     */
    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef)
    {
        throw new S2Container_UnsupportedOperationException("addDestroyMethodDef");
    }

    /**
     * @see S2Container_ComponentDef::addAspectDef()
     */
    public function addAspectDef(S2Container_AspectDef $aspectDef)
    {
        throw new S2Container_UnsupportedOperationException("addAspectDef");
    }

    /**
     * @see S2Container_ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize()
    {
        throw new S2Container_UnsupportedOperationException("getArgDefSize");
    }

    /**
     * @see S2Container_PropertyDefAware::getPropertyDefSize()
     */
    public function getPropertyDefSize()
    {
        throw new S2Container_UnsupportedOperationException("getPropertyDefSize");
    }

    /**
     * @see S2Container_InitMethodDefAware::getInitMethodDefSize()
     */
    public function getInitMethodDefSize()
    {
        throw new S2Container_UnsupportedOperationException("getInitMethodDefSize");
    }
    
    /**
     * @see S2Container_DestroyMethodDefAware::getDestroyMethodDefSize()
     */
    public function getDestroyMethodDefSize()
    {
        throw new S2Container_UnsupportedOperationException("getDestroyMethodDefSize");
    }

    /**
     * @see S2Container_AspectDefAware::getAspectDefSize()
     */
    public function getAspectDefSize()
    {
        throw new S2Container_UnsupportedOperationException("getAspectDefSize");
    }

    /**
     * @see S2Container_ArgDefAware::getArgDef()
     */
    public function getArgDef($index)
    {
        throw new S2Container_UnsupportedOperationException("getArgDef");
    }

    /**
     * @see S2Container_PropertyDefAware::getPropertyDef()
     */
    public function getPropertyDef($index)
    {
        throw new S2Container_UnsupportedOperationException("getPropertyDef");
    }

    /**
     * @see S2Container_PropertyDefAware::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName)
    {
        throw new S2Container_UnsupportedOperationException("hasPropertyDef");
    }

    /**
     * @see S2Container_InitMethodDefAware::getInitMethodDef()
     */
    public function getInitMethodDef($index)
    {
        throw new S2Container_UnsupportedOperationException("getInitMethodDef");
    }
    
    /**
     * @see S2Container_DestroyMethodDefAware::getDestroyMethodDef()
     */
    public function getDestroyMethodDef($index)
    {
        throw new S2Container_UnsupportedOperationException("getDestroyMethodDef");
    }

    /**
     * @see S2Container_AspectDefAware::getAspectDef()
     */
    public function getAspectDef($index)
    {
        throw new S2Container_UnsupportedOperationException("getAspectDef");
    }
    
    /**
     * @see S2Container_MetaDefAware::addMetaDef()
     */
    public function addMetaDef(S2Container_MetaDef $metaDef)
    {
        throw new S2Container_UnsupportedOperationException("addMetaDef");
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDef()
     */
    public function getMetaDef($index)
    {
        throw new S2Container_UnsupportedOperationException("getMetaDef");
    }

    /**
     * @see S2Container_MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name)
    {
        throw new S2Container_UnsupportedOperationException("getMetaDefs");
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize()
    {
        throw new S2Container_UnsupportedOperationException("getMetaDefSize");
    }

    /**
     * @see S2Container_ComponentDef::getExpression()
     */
    public function getExpression()
    {
        throw new S2Container_UnsupportedOperationException("getExpression");
    }

    /**
     * @see S2Container_ComponentDef::setExpression()
     */
    public function setExpression($str)
    {
        throw new S2Container_UnsupportedOperationException("setExpression");
    }
    
    /**
     * @see S2Container_ComponentDef::getInstanceMode()
     */
    public function getInstanceMode()
    {
        throw new S2Container_UnsupportedOperationException("getInstanceMode");
    }

    /**
     * @see S2Container_ComponentDef::setInstanceMode()
     */
    public function setInstanceMode($instanceMode)
    {
        throw new S2Container_UnsupportedOperationException("setInstanceMode");
    }

    /**
     * @see S2Container_ComponentDef::getAutoBindingMode()
     */
    public function getAutoBindingMode()
    {
        throw new S2Container_UnsupportedOperationException("getAutoBindingMode");
    }

    /**
     * @see S2Container_ComponentDef::setAutoBindingMode()
     */
    public function setAutoBindingMode($autoBindingMode)
    {
        throw new S2Container_UnsupportedOperationException("setAutoBindingMode");
    }

    /**
     * @see S2Container_ComponentDef::init()
     */
    public function init()
    {
    }

    /**
     * @see S2Container_ComponentDef::destroy()
     */
    public function destroy()
    {
    }

    /**
     * @see S2Container_ComponentDef::reconstruct()
     */
    public function reconstruct($mode =
                            S2Container_ComponentDef::RECONSTRUCT_NORMAL)
    {
        if ($mode == S2Container_ComponentDef::RECONSTRUCT_NORMAL) {
            return false;
        }
        $this->componentClass_ = new ReflectionClass($this->componentClassName_);
        return true;
    }
}
?>
