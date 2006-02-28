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
 * @package org.seasar.extension.autoregister.factory
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
        $componentStr = $beanDesc->getConstant(S2Container_AbstractAnnotationHandler::COMPONENT);

        $items = preg_split("/[=,]+/",$componentStr,-1,PREG_SPLIT_NO_EMPTY);
        $componentDef = $this->createComponentDefInternal($componentClass,$instanceMode);
        $c = count($items);
        for ($i = 0; $i < $c; $i += 2) {
            $key = trim($items[$i]);
            $value = trim($items[$i + 1]);
            if (strcasecmp($key,S2Container_AbstractAnnotationHandler::NAME) == 0) {
                $componentDef->setComponentName($value);
            } else if (strcasecmp($key,S2Container_AbstractAnnotationHandler::INSTANCE) == 0) {
                $componentDef->setInstanceMode($value);
            } else if (strcasecmp($key,S2Container_AbstractAnnotationHandler::AUTO_BINDING) == 0) {
                $componentDef->setAutoBindingMode($value);
            } else {
                throw new S2Container_IllegalArgumentException("$componentStr [ $key ] [ $value ] ");
            }
        }
        
        return $componentDef;
    }

    public function createPropertyDef(S2Container_BeanDesc $beanDesc,
                                      S2Container_PropertyDesc $propertyDesc) {

        $propName = $propertyDesc->getPropertyName();
        $fieldName = $propName . S2Container_AbstractAnnotationHandler::BINDING_SUFFIX;
        if (!$beanDesc->hasConstant($fieldName)) {
            return null;
        }
        
        $bindingTypeName = null;
        $expression = trim($beanDesc->getConstant($fieldName));
        return $this->createPropertyDefInternal($propName, 
                                                $expression,
                                                $bindingTypeName);

    }

    public function appendAspect(S2Container_ComponentDef $componentDef) {

        $componentClass = $componentDef->getComponentClass();
        if ($componentClass == null) {
            return;
        }
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($componentClass);
        if (!$beanDesc->hasConstant(S2Container_AbstractAnnotationHandler::ASPECT)) {
            return;
        }
        $aspectStr = $beanDesc->getConstant(S2Container_AbstractAnnotationHandler::ASPECT);
        $items = preg_split("/[=,]+/",$aspectStr,-1,PREG_SPLIT_NO_EMPTY);
        $interceptor = null;
        $pointcut = null;
        if (count($items) == 1) {
            $interceptor = trim($items[0]);
        } else {
            $c = count($items);
            for ($i = 0; $i < $c; $i += 2) {
                $key = trim($items[$i]);
                $value = trim($items[$i + 1]);
                if (strcasecmp($key,S2Container_AbstractAnnotationHandler::INTERCEPTOR) == 0) {
                    $interceptor = $value;
                } else if (strcasecmp($key,S2Container_AbstractAnnotationHandler::POINTCUT) == 0) {
                    $pointcut = $value;
                } else {
                    throw new S2Container_IllegalArgumentException("$aspectStr [ $key ] [ $value ]");
                }
            }
        }
        $this->appendAspectInternal($componentDef, $interceptor, $pointcut);
    
    }

    public function appendInitMethod(S2Container_ComponentDef $componentDef) {
 
        $componentClass = $componentDef->getComponentClass();
        if ($componentClass == null) {
            return;
        }
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($componentClass);
        if (!$beanDesc->hasConstant(S2Container_AbstractAnnotationHandler::INIT_METHOD)) {
            return;
        }
        $initMethodStr = $beanDesc->getConstant(S2Container_AbstractAnnotationHandler::INIT_METHOD);
        if ($initMethodStr == '') {
            return;
        }
        $items = preg_split("/[,]+/",$initMethodStr,-1,PREG_SPLIT_NO_EMPTY);
        $c = count($items);
        for ($i = 0; $i < $c; ++$i) {
            $methodName = trim($items[$i]);
            if (!$beanDesc->hasMethod($methodName)) {
                throw new S2Container_IllegalInitMethodAnnotationRuntimeException($componentClass, $methodName);
            }
            $method = $beanDesc->getMethods($methodName);
            if ($method->getNumberOfParameters() != 0) {
                throw new S2Container_IllegalInitMethodAnnotationRuntimeException($componentClass, $methodName);
            }
            if (!$this->isInitMethodRegisterable($componentDef, $methodName)) {
                continue;
            }
            $this->appendInitMethodInternal($componentDef, $methodName);
        }
    }
}
?>
