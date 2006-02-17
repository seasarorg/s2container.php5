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
 * @package org.seasar.extension.annotation.factory
 * @author klove
 */
class S2Container_ConstantAnnotationHandler 
    extends S2Container_AbstractAnnotationHandler
{
    public function createComponentDef(ReflectionClass $componentClass,
                                                         $instanceMode) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($componentClass);
        if (!$beanDesc->hasConstant(S2Container_AbstractAnnotationHandler::COMPONENT)) {
            return $this->createComponentDefInternal($componentClass,$instanceMode);
        }
        $field = $beanDesc->getConstant(S2Container_AbstractAnnotationHandler::COMPONENT);
print "$field \n";
/*
        $componentStr = (String) FieldUtil.get(field, null);
        String[] array = StringUtil.split(componentStr, "=, ");
        ComponentDef componentDef = createComponentDefInternal(componentClass, instanceDef);
        for (int i = 0; i < array.length; i += 2) {
            String key = array[i].trim();
            String value = array[i + 1].trim();
            if (NAME.equalsIgnoreCase(key)) {
                componentDef.setComponentName(value);
            } else if (INSTANCE.equalsIgnoreCase(key)) {
                componentDef.setInstanceDef(
                        InstanceDefFactory.getInstanceDef(value));
            } else if (AUTO_BINDING.equalsIgnoreCase(key)) {
                componentDef.setAutoBindingDef(
                        AutoBindingDefFactory.getAutoBindingDef(value));
            } else {
                throw new IllegalArgumentException(componentStr);
            }
        }
        */
        return $componentDef;
    }

    public function createPropertyDef(S2Container_BeanDesc $beanDesc,
                                      S2Container_PropertyDesc $propertyDesc) {
/*
        String propName = propertyDesc.getPropertyName();
        String fieldName = propName + BINDING_SUFFIX;
        if (!beanDesc.hasField(fieldName)) {
            return null;
        }
        String bindingStr = (String) beanDesc.getFieldValue(fieldName, null);
        String bindingTypeName = null;
        String expression = null;
        if (bindingStr != null) {
            String[] array = StringUtil.split(bindingStr, "=, ");
            if (array.length == 1) {
                expression = array[0];
            } else {
                for (int i = 0; i < array.length; i += 2) {
                    String key = array[i].trim();
                    String value = array[i + 1].trim();
                    if (BINDING_TYPE.equalsIgnoreCase(key)) {
                        bindingTypeName = value;
                    } else if (VALUE.equalsIgnoreCase(key)) {
                        expression = value;
                    } else {
                        throw new IllegalArgumentException(bindingStr);
                    }
                }
            }
        }
        return createPropertyDef(propName, expression, bindingTypeName);
*/
    }

    public function appendAspect(S2Container_ComponentDef $componentDef) {
/*
        Class componentClass = componentDef.getComponentClass();
        if (componentClass == null) {
            return;
        }
        BeanDesc beanDesc = BeanDescFactory.getBeanDesc(componentClass);
        if (!beanDesc.hasField(ASPECT)) {
            return;
        }
        String aspectStr = (String) beanDesc.getFieldValue(ASPECT, null);
        String[] array = StringUtil.split(aspectStr, "=, ");
        String interceptor = null;
        String pointcut = null;
        if (array.length == 1) {
            interceptor = array[0];
        } else {
            for (int i = 0; i < array.length; i += 2) {
                String key = array[i].trim();
                String value = array[i + 1].trim();
                if (VALUE.equalsIgnoreCase(key)) {
                    interceptor = value;
                } else if (POINTCUT.equalsIgnoreCase(key)) {
                    pointcut = value;
                } else {
                    throw new IllegalArgumentException(aspectStr);
                }
            }
        }
        appendAspect(componentDef, interceptor, pointcut);
*/    
    }

    public function appendAspectInternal(S2Container_ComponentDef $componentDef,
                                 $interceptor,
                                 $pointcut) {
        
        if ($interceptor == null) {
            throw new S2Container_EmptyRuntimeException("interceptor");
        }
        
        if(is_string($pointcut)){
            $aspectDef = new S2Container_AspectDefImpl(
                             new S2Container_PointcutImpl(explode(" ",$pointcut)));
        }else{
            $aspectDef = new S2Container_AspectDefImpl(
                             new S2Container_PointcutImpl(
                             $componentDef->getComponentClass()));
        }
        S2Container_ChildComponentDefBindingUtil::put($interceptor,
                                                      $aspectDef);
        $aspectDef->setExpression($interceptor);
        $componentDef->addAspectDef($aspectDef);
    }

    public function appendInitMethod(S2Container_ComponentDef $componentDef) {
/* 
        Class componentClass = componentDef.getComponentClass();
        if (componentClass == null) {
            return;
        }
        BeanDesc beanDesc = BeanDescFactory.getBeanDesc(componentClass);
        if (!beanDesc.hasField(INIT_METHOD)) {
            return;
        }
        String initMethodStr = (String) beanDesc.getFieldValue(INIT_METHOD, null);
        if (StringUtil.isEmpty(initMethodStr)) {
            return;
        }
        String[] array = StringUtil.split(initMethodStr, ", ");
        for (int i = 0; i < array.length; ++i) {
            String methodName = array[i].trim();
            if (!beanDesc.hasMethod(methodName)) {
                throw new IllegalInitMethodAnnotationRuntimeException(componentClass, methodName);
            }
            Method[] methods = beanDesc.getMethods(methodName);
            if (methods.length != 1 || methods[0].getParameterTypes().length != 0) {
                throw new IllegalInitMethodAnnotationRuntimeException(componentClass, methodName);
            }
            if (!isInitMethodRegisterable(componentDef, methodName)) {
                continue;
            }
            appendInitMethod(componentDef, methodName);
        }
*/    
    }

    protected function appendInitMethodInternal(S2Container_ComponentDef $componentDef,
                                         $methodName) {
        $initMethodDef = new S2Container_InitMethodDefImpl($methodName);
        $componentDef->addInitMethodDef($initMethodDef);
    }
}
?>
