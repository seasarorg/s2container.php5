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
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
abstract class S2Container_AbstractMethodAssembler
    extends S2Container_AbstractAssembler
    implements S2Container_MethodAssembler
{
    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        parent::__construct($componentDef);
    }
    
    /**
     * @param S2Container_BeanDesc
     * @param S2Container_ComponentDef
     * @param S2Container_MethodDef
     */
    protected function invoke(S2Container_BeanDesc $beanDesc,
                              $component,
                              S2Container_MethodDef $methodDef)
    {
        $expression = $methodDef->getExpression();
        $methodName = $methodDef->getMethodName();
        if ($methodName != null) {
            $args = array();
            $method = null;
            try {
                if ($methodDef->getArgDefSize() > 0) {
                    $args = $methodDef->getArgs();
                } else {
                    $methods = $beanDesc->getMethods($methodName);
                    $method = $this->_getSuitableMethod($methods);

                    if ($method != null) {
                        $args = $this->getArgs($method->getParameters());
                    }
                }
            } catch (S2Container_ComponentNotFoundRuntimeException $cause) {
                throw new S2Container_IllegalMethodRuntimeException($this->
                                              getComponentClass($component),
                                              $methodName,
                                              $cause);
            }
            if ($method != null) {
                S2Container_MethodUtil::invoke($method, $component, $args);
            } else {
                $this->_invoke0($beanDesc,$component,$methodName,$args);
            }
        } else {
            $this->_invokeExpression($component,$expression);
        }
    }
    
    /**
     * @param S2Container_ComponentDef
     * @param string 
     */
    private function _invokeExpression($component,$expression)
    {
        $exp = S2Container_EvalUtil::addSemiColon($expression);
        eval($exp);
    }
    
    /**
     * 
     */
    private function _getSuitableMethod($methods)
    {
        $params = $methods->getParameters();
        $suitable = 1;
        if (count($params) == 0) {
            return $methods;
        }
        foreach ($params as $param) {
            if ($param->getClass() != null) {
                if (!S2Container_AutoBindingUtil::isSuitable($param->getClass())) {
                    $suitable *= 0;
                }                    
            }
        }

        if ($suitable == 1) {
            return $methods;
        }

        return null;
    }

    /**
     * 
     */
    private function _invoke0(S2Container_BeanDesc $beanDesc,
                             $component,
                             $methodName,
                             $args)
    {
        try {
            $beanDesc->invoke($component,$methodName,$args);
        } catch (Exception $ex) {
            throw new S2Container_IllegalMethodRuntimeException($this->
                                                 getComponentClass($component),
                                                 $methodName,
                                                 $ex);
        }
    }
}
?>
