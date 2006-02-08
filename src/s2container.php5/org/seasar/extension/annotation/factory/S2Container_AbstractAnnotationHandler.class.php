<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
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
abstract class S2Container_AbstractAnnotationHandler 
    implements S2Container_AnnotationHandler {

    const COMPONENT = "COMPONENT";

    const NAME = "name";

    const INSTANCE = "instance";

    const AUTO_BINDING = "autoBinding";

    const BINDING_SUFFIX = "_BINDING";
    
    const BINDING_TYPE = "bindingType";

    const VALUE = "value";
    
    const ASPECT = "ASPECT";
    
    const INIT_METHOD = "INIT_METHOD";
    
    const INTERCEPTOR = "interceptor";
    
    const POINTCUT = "pointcut";

/*
    public function createComponentDef($className,$instanceMode) {
        return $this->createComponentDef(new ReflectionClass($className), $instanceMode);
    }
*/
    public function appendDI(S2Container_ComponentDef $componentDef) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($componentDef->getComponentClass());
        $c = $beanDesc->getPropertyDescSize();
        for ($i = 0; $i < $c; ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            if (!$pd->hasWriteMethod()) {
                continue;
            }
            $propDef = $this->createPropertyDef($beanDesc, $pd);
            if ($propDef == null) {
                continue;
            }
            $componentDef->addPropertyDef($propDef);
        }
    }
    
    protected function createComponentDefInternal(ReflectionClass $componentClass,
                                                  $instanceMode) {
        $componentDef = new S2Container_ComponentDefImpl($componentClass);
        if ($instanceMode != null) {
            $componentDef->setInstanceMode($instanceMode);
        }
        return $componentDef;
    }

    protected function createPropertyDefInternal($propertyName,
                                         $expression, 
                                         $bindingTypeName) {
        $propertyDef = new S2Container_PropertyDefImpl($propertyName);

        /*
        if (!StringUtil.isEmpty(bindingTypeName)) {
            BindingTypeDef bindingTypeDef = BindingTypeDefFactory.getBindingTypeDef(bindingTypeName);
            propertyDef.setBindingTypeDef(bindingTypeDef);
        }
        */
        
        if (is_string($expression) and $expression != "") {
            S2Container_ChildComponentDefBindingUtil::put($expression,
                                                             $propertyDef);
            $propertyDef->setExpression($expression);
        }
        return $propertyDef;
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

    protected function appendInitMethodInternal(S2Container_ComponentDef $componentDef,
                                         $methodName) {
        $initMethodDef = new S2Container_InitMethodDefImpl($methodName);
        $componentDef->addInitMethodDef($initMethodDef);
    }

/*    
    protected function isInitMethodRegisterable(S2Container_ComponentDef $cd, 
                                                $methodName) {
        if (!is_string($methodName) or $methodName == "") {
            return false;
        }
        
        $c = $cd->getInitMethodDefSize();
        for ($i = 0; $i < $c; ++$i) {
            $other = $cd->getInitMethodDef($i);
            if ($methodName == $other->getMethodName() and   ///
                $other->getArgDefSize() == 0) {              ///
                return false;                                ///
            }
        }
        return true;
    }
*/
}
?>
