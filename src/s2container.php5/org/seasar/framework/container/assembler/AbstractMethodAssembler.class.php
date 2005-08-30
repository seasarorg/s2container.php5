<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id: AbstractMethodAssembler.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
abstract class AbstractMethodAssembler
    extends AbstractAssembler
    implements MethodAssembler {

    public function AbstractMethodAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }
    
    protected function invoke(
        BeanDesc $beanDesc,
        $component,
        MethodDef $methodDef) {

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
                    $method = $this->getSuitableMethod($methods);

                    if ($method != null) {
                        $args = $this->getArgs($method->getParameters());
                    }
                }
            } catch (ComponentNotFoundRuntimeException $cause) {
                throw new IllegalMethodRuntimeException(
                    $this->getComponentClass($component),
                    $methodName,
                    $cause);
            }
            if ($method != null) {
                MethodUtil::invoke($method, $component, $args);
            } else {
                $this->invoke0($beanDesc,$component,$methodName,$args);
            }
        } else {
            $this->invokeExpression($component,$expression);
        }
    }
    
    private function invokeExpression($component,$expression) {
    	$exp = EvalUtil::addSemiColon($expression);
    	eval($exp);
    }
    
    private function getSuitableMethod($methods) {
        $params = $methods->getParameters();
        $suitable = 1;
        if(count($params) == 0){
            return $methods;
        }
        foreach($params as $param){
            if($param->getClass() != null){
                if(!AutoBindingUtil::isSuitable($param->getClass())){
                    $suitable *= 0;
                }                    
            }
        }

        if($suitable == 1){
            return $methods;
        }

        return null;
    }

    private function invoke0(
        BeanDesc $beanDesc,
        $component,
        $methodName,
        $args){

        try {
            $beanDesc->invoke($component,$methodName,$args);
        } catch (Exception $ex) {
            throw new IllegalMethodRuntimeException(
                $this->getComponentClass($component),
                $methodName,
                $ex);
        }
    }
}
?>