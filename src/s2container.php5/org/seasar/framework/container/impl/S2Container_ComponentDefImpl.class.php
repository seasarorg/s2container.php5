<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// | Authors: klove, nowel                                                |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 * @author nowel
 */
class S2Container_ComponentDefImpl 
    implements S2Container_ComponentDef
{
    private $componentClass_;

    private $componentClassName_;

    private $componentName_;

    private $concreteClass_;

    private $container_;

    private $expression_;

    private $argDefSupport_;

    private $propertyDefSupport_;

    private $initMethodDefSupport_;

    private $destroyMethodDefSupport_;

    private $aspectDefSupport_;
    
    private $metaDefSupport_;
    
    private $interTypeDefSupport_;

    private $instanceMode_ = S2Container_ContainerConstants::INSTANCE_SINGLETON;

    private $autoBindingMode_ = S2Container_ContainerConstants::AUTO_BINDING_AUTO;

    private $componentDeployer_;

    /**
     * @param ReflectionClass component name
     * @param string component name  
     */
    public function __construct($componentClass = "",
                                $componentName = "")
    {
        if ($componentClass instanceof ReflectionClass) {
            $this->componentClass_ = $componentClass;
            $this->componentClassName_ = $componentClass->getName();
        } else {
            if (class_exists($componentClass) ||
               interface_exists($componentClass)) {
               $this->componentClass_ = new ReflectionClass($componentClass);
            }
            $this->componentClassName_ = $componentClass;
        }
        $this->componentName_ = $componentName;
        $this->argDefSupport_ = new S2Container_ArgDefSupport();
        $this->propertyDefSupport_ = new S2Container_PropertyDefSupport();
        $this->initMethodDefSupport_ = new S2Container_InitMethodDefSupport();
        $this->destroyMethodDefSupport_ = new S2Container_DestroyMethodDefSupport();
        $this->aspectDefSupport_ = new S2Container_AspectDefSupport();
        $this->metaDefSupport_ = new S2Container_MetaDefSupport();
        $this->interTypeDefSupport_ = new S2Container_InterTypeDefSupport();
    }

    /**
     * @see S2Container_ComponentDef::getComponent()
     */
    public function getComponent()
    {
        return $this->_getComponentDeployer()->deploy();
    }

    /**
     * @see S2Container_ComponentDef::injectDependency()
     */
    public function injectDependency($outerComponent)
    {
        $this->_getComponentDeployer()->injectDependency($outerComponent);
    }

    /**
     * @see S2Container_ComponentDef::getComponentClass()
     */
    public final function getComponentClass()
    {
        return $this->componentClass_;
    }

    /**
     * @see S2Container_ComponentDef::setComponentClass()
     */
    public final function setComponentClass(ReflectionClass $componentClass)
    {
        $this->componentClass_ = $componentClass;
    }

    /**
     * @see S2Container_ComponentDef::getComponentName()
     */
    public final function getComponentName()
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
    public final function getConcreteClass()
    {
        return $this->componentClass_;
    }

    /**
     * @see S2Container_ComponentDef::getContainer()
     */
    public final function getContainer()
    {
        return $this->container_;
    }

    /**
     * @see S2Container_ComponentDef::setContainer()
     */
    public final function setContainer(S2Container $container)
    {
        $this->container_ = $container;
        $this->argDefSupport_->setContainer($container);
        $this->metaDefSupport_->setContainer($container);
        $this->propertyDefSupport_->setContainer($container);
        $this->interTypeDefSupport_->setContainer($container);
        $this->initMethodDefSupport_->setContainer($container);
        $this->destroyMethodDefSupport_->setContainer($container);
        $this->aspectDefSupport_->setContainer($container);
    }

    /**
     * @see S2Container_ComponentDef::addArgDef()
     */
    public function addArgDef(S2Container_ArgDef $argDef)
    {
        $this->argDefSupport_->addArgDef($argDef);
    }

    /**
     * @see S2Container_ComponentDef::addPropertyDef()
     */
    public function addPropertyDef(S2Container_PropertyDef $propertyDef)
    {
        $this->propertyDefSupport_->addPropertyDef($propertyDef);
    }

    /**
     * @see S2Container_InitMethodDefAware::addInitMethodDef()
     */
    public function addInitMethodDef(S2Container_InitMethodDef $methodDef)
    {
        $this->initMethodDefSupport_->addInitMethodDef($methodDef);
    }

    /**
     * @see S2Container_DestroyMethodDefAware::addDestroyMethodDef()
     */
    public function addDestroyMethodDef(S2Container_DestroyMethodDef $methodDef)
    {
        $this->destroyMethodDefSupport_->addDestroyMethodDef($methodDef);
    }

    /**
     * @see S2Container_ComponentDef::addAspectDef()
     */
    public function addAspectDef(S2Container_AspectDef $aspectDef)
    {
        $this->aspectDefSupport_->addAspectDef($aspectDef);
        $this->concreteClass_ = null;
    }

    /**
     * @see S2Container_ArgDefAware::getArgDefSize()
     */
    public function getArgDefSize()
    {
        return $this->argDefSupport_->getArgDefSize();
    }

    /**
     * @see S2Container_PropertyDefAware::getPropertyDefSize()
     */
    public function getPropertyDefSize()
    {
        return $this->propertyDefSupport_->getPropertyDefSize();
    }

    /**
     * @see S2Container_InitMethodDefAware::getInitMethodDefSize()
     */
    public function getInitMethodDefSize()
    {
        return $this->initMethodDefSupport_->getInitMethodDefSize();
    }

    /**
     * @see S2Container_DestroyMethodDefAware::getDestroyMethodDefSize()
     */
    public function getDestroyMethodDefSize()
    {
        return $this->destroyMethodDefSupport_->getDestroyMethodDefSize();
    }

    /**
     * @see S2Container_AspectDefAware::getAspectDefSize()
     */
    public function getAspectDefSize()
    {
        return $this->aspectDefSupport_->getAspectDefSize();
    }

    /**
     * @see S2Container_ComponentDef::getInstanceMode()
     */
    public function getInstanceMode()
    {
        return $this->instanceMode_;
    }

    /**
     * @see S2Container_ComponentDef::setInstanceMode()
     */
    public function setInstanceMode($instanceMode)
    {
        if (S2Container_InstanceModeUtil::isSingleton($instanceMode)
                || S2Container_InstanceModeUtil::isPrototype($instanceMode)
                || S2Container_InstanceModeUtil::isRequest($instanceMode)
                || S2Container_InstanceModeUtil::isSession($instanceMode)
                || S2Container_InstanceModeUtil::isOuter($instanceMode)) {
            $this->instanceMode_ = $instanceMode;
        } else {
            throw new S2Container_IllegalArgumentException($instanceMode);
        }
    }

    /**
     * @see S2Container_ComponentDef::getAutoBindingMode()
     */
    public function getAutoBindingMode()
    {
        return $this->autoBindingMode_;
    }

    /**
     * @see S2Container_ComponentDef::setAutoBindingMode()
     */
    public function setAutoBindingMode($autoBindingMode)
    {
        if (S2Container_AutoBindingUtil::isAuto($autoBindingMode)
                || S2Container_AutoBindingUtil::isConstructor($autoBindingMode)
                || S2Container_AutoBindingUtil::isProperty($autoBindingMode)
                || S2Container_AutoBindingUtil::isNone($autoBindingMode)) {

            $this->autoBindingMode_ = $autoBindingMode;
        } else {
            throw new S2Container_IllegalArgumentException($autoBindingMode);
        }
    }

    /**
     * @see S2Container_ComponentDef::init()
     */
    public function init()
    {
        $this->_getComponentDeployer()->init();
    }

    /**
     * @see S2Container_ComponentDef::destroy()
     */
    public function destroy()
    {
        $this->_getComponentDeployer()->destroy();
    }

    /**
     * @see S2Container_ComponentDef::reconstruct()
     */
    public function reconstruct($mode = 
                                S2Container_ComponentDef::RECONSTRUCT_NORMAL)
    {

        if ($mode == S2Container_ComponentDef::RECONSTRUCT_NORMAL and
           $this->componentClass_ != null) {
            return false;
        }

        if (class_exists($this->componentClassName_) or
           interface_exists($this->componentClassName_)) {
            $this->componentClass_ = new ReflectionClass($this->
                                                      componentClassName_);
            return true;
        }

        return false;
    }

    /**
     * @see S2Container_ComponentDef::getExpression()
     */
    public function getExpression()
    {
        return $this->expression_;
    }

    /**
     * @see S2Container_ComponentDef::setExpression()
     */
    public function setExpression($expression)
    {
        $this->expression_ = $expression;
    }

    /**
     * @see S2Container_ArgDefAware::getArgDef()
     */
    public function getArgDef($index)
    {
        return $this->argDefSupport_->getArgDef($index);
    }

    /**
     * @see S2Container_PropertyDefAware::getPropertyDef()
     */
    public function getPropertyDef($index)
    {
        return $this->propertyDefSupport_->getPropertyDef($index);
    }

    /**
     * @see S2Container_PropertyDefAware::hasPropertyDef()
     */
    public function hasPropertyDef($propertyName)
    {
        return $this->propertyDefSupport_->hasPropertyDef($propertyName);
    }

    /**
     * @see S2Container_InitMethodDefAware::getInitMethodDef()
     */
    public function getInitMethodDef($index)
    {
        return $this->initMethodDefSupport_->getInitMethodDef($index);
    }

    /**
     * @see S2Container_DestroyMethodDefAware::getDestroyMethodDef()
     */
    public function getDestroyMethodDef($index)
    {
        return $this->destroyMethodDefSupport_->getDestroyMethodDef($index);
    }

    /**
     * @see S2Container_AspectDefAware::getAspectDef()
     */
    public function getAspectDef($index)
    {
        return $this->aspectDefSupport_->getAspectDef($index);
    }

    /**
     * @see S2Container_MetaDefAware::addMetaDef()
     */
    public function addMetaDef(S2Container_MetaDef $metaDef)
    {
        $this->metaDefSupport_->addMetaDef($metaDef);
    }

    /**
     * @see S2Container_MetaDefAware::getMetaDef()
     */
    public function getMetaDef($name)
    {
        return $this->metaDefSupport_->getMetaDef($name);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDefs()
     */
    public function getMetaDefs($name)
    {
        return $this->metaDefSupport_->getMetaDefs($name);
    }
    
    /**
     * @see S2Container_MetaDefAware::getMetaDefSize()
     */
    public function getMetaDefSize()
    {
        return $this->metaDefSupport_->getMetaDefSize();
    }
    
    /**
     * @see org.seasar.framework.container.ComponentDef#addInterTypeDef(org.seasar.framework.container.InterTypeDef)
     */
    public function addInterTypeDef(S2Container_InterTypeDef $interTypeDef){
        $this->interTypeDefSupport_->addInterTypeDef($interTypeDef);
    }
    
    /**
     * @see org.seasar.framework.container.InterTypeDefAware#getInterTypeDef(int)
     */
    public function getInterTypeDef($index) {
        return $this->interTypeDefSupport_->getInterTypeDef($index);
    }
    
    /**
     * @see org.seasar.framework.container.InterTypeDefAware#getInterTypeDefSize()
     */
    public function getInterTypeDefSize() {
        return $this->interTypeDefSupport_->getInterTypeDefSize();
    }

    /**
     * 
     */
    private function _getComponentDeployer()
    {
        if ($this->componentDeployer_ == null) {
            if ($this->expression_ == null && 
               $this->componentClass_ == null) {
                throw new S2Container_S2RuntimeException('ESSR1008',
                           array($this->componentName_,
                                 $this->componentClassName_));
            }
            $this->componentDeployer_ = 
                S2Container_ComponentDeployerFactory::create($this);
        }
        return $this->componentDeployer_;
    }   
}
?>
