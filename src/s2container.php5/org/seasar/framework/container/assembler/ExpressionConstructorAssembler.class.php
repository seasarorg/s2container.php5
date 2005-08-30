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
// $Id$
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
final class ExpressionConstructorAssembler
    extends AbstractConstructorAssembler {

    /**
     * @param ComponentDef
     */
    public function ExpressionConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    public function assemble() {
        $cd = $this->getComponentDef();
        $container = $cd->getContainer();
        $expression = $cd->getExpression();
        $componentClass = $cd->getComponentClass();
        $component = null;

        if($container->hasComponentDef($expression)){
            $component = $container->getComponent($expression);
        }else{
            $exp = EvalUtil::getExpression($expression);
            $component = eval($exp);
        }
        
        if(!is_object($component)){
        	throw new S2RuntimeException('ESSR0017',array("eval() result isnt an Object. "));
        }
        if($componentClass instanceof ReflectionClass){
     		$refExpClass = new ReflectionClass($component);
       		if(!$refExpClass->isSubclassOf($componentClass)){
      		    throw new ClassUnmatchRuntimeException($componentClass,$refExpClass);	
       		}
       	}
        $cd->setComponentClass(new ReflectionClass($component));
        return $component;        
    }
}
?>