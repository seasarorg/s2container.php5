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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.autoregister.factory
 * @author klove
 */
class S2Container_CommentAnnotationHandler 
    extends S2Container_AbstractAnnotationHandler 
{
    public function createComponentDef(ReflectionClass $componentClass,
                                       $instanceMode) 
    {
        if (S2Container_Annotations::isAnnotationPresent('S2Container_ComponentAnnotation',
                                                  $componentClass)) {
            $component = S2Container_Annotations::getAnnotation('S2Container_ComponentAnnotation',
                                                                $componentClass);
        } else {
            return $this->createComponentDefInternal($componentClass, $instanceMode);
        }

        $componentDef = $this->createComponentDefInternal($componentClass,$instanceMode);
        $componentDef->setComponentName($component->name);
        if ($component->instance != null) {
            $componentDef->setInstanceMode($component->instance);
        }

        if ($component->autoBinding != null) {
            $componentDef->setAutoBindingMode($component->autoBinding);
        }
        return $componentDef;
    }

    /**
     * 
     */
    public function createPropertyDef(S2Container_BeanDesc $beanDesc,
                                      S2Container_PropertyDesc $propertyDesc) 
    {
        if (!$propertyDesc->hasWriteMethod()) {
            return null;
        }
        
        $method = $propertyDesc->getWriteMethod();
        $binding = null;
        if (S2Container_Annotations::isAnnotationPresent('S2Container_BindingAnnotation',
                                                  $beanDesc->getBeanClass(),
                                                  $method->getName())) {
            $binding = S2Container_Annotations::getAnnotation('S2Container_BindingAnnotation',
                                               $beanDesc->getBeanClass(),
                                               $method->getName());
        }
 
        $propName = $propertyDesc->getPropertyName();
        if ($binding != null) {
            $bindingTypeName = $binding->bindingType;
            $expression = $binding->value;
            return $this->createPropertyDefInternal($propName,$expression,$bindingTypeName);
        }
        return null;
    }
    
    /**
     * 
     */
    public function appendAspect(S2Container_ComponentDef $componentDef)
    {
        $clazz = $componentDef->getComponentClass();
        $aspect = null;
        if (S2Container_Annotations::isAnnotationPresent('S2Container_AspectAnnotation',
                                                  $clazz)) {
            $aspect = S2Container_Annotations::getAnnotation('S2Container_AspectAnnotation',
                                                  $clazz);
        }

        if ($aspect != null) {
            $aspect->value != null ?
                $interceptor = $aspect->value :
                $interceptor = $aspect->interceptor;
            $pointcut = $aspect->pointcut;
            $this->appendAspectInternal($componentDef,$interceptor,$pointcut);
        }
        
        $methods = $clazz->getMethods();
        foreach ($methods as $method) {
            $mAspect = null;
            if (S2Container_Annotations::isAnnotationPresent('S2Container_AspectAnnotation',
                                                  $clazz,
                                                  $method->getName())) {
                $mAspect = S2Container_Annotations::getAnnotation(
                                         'S2Container_AspectAnnotation',
                                         $clazz,
                                         $method->getName());
            }
            if ($mAspect != null) {
                $mAspect->value != null ?
                    $interceptor = $mAspect->value :
                    $interceptor = $mAspect->interceptor;
                
                $this->appendAspectInternal($componentDef,$interceptor,$method->getName());
            }
        }
    }
    
    /**
     * 
     */
    public function appendInitMethod(S2Container_ComponentDef $componentDef)
    {
        $componentClass = $componentDef->getComponentClass();
        if ($componentClass == null) {
            return;
        }
        $methods = $componentClass->getMethods();
        $initMethod = null;
        foreach ($methods as $method) {
            if (!S2Container_Annotations::isAnnotationPresent('S2Container_InitMethodAnnotation',
                                                  $componentClass,
                                                  $method->getName())) {
                continue;
            }
            if (count($method->getParameters()) != 0) {
                throw new S2Container_IllegalInitMethodAnnotationRuntimeException($componentClass, $method->getName());
            }

            if (!$this->isInitMethodRegisterable($componentDef,$method->getName())) {
                continue;
            }

            $this->appendInitMethodInternal($componentDef, $method->getName());
        }
        return null;
    }
}
?>
