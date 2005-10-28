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
class AutoConstructorAssembler
    extends AbstractConstructorAssembler {

    public function AutoConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    public function assemble(){
        $args = array();
        $refMethod = $this->getComponentDef()->getConcreteClass()->getConstructor();
        if($refMethod != null){
            $args = $this->getArgs($this->getComponentDef()->getConcreteClass()->getConstructor()->getParameters());
        }
        if($this->getComponentDef() != null and 
           $this->getComponentDef()->getAspectDefSize()>0){
            return AopProxyUtil::getProxyObject($this->getComponentDef(),$args); 
        }                
        return ConstructorUtil::newInstance($this->getComponentDef()->getConcreteClass(), $args);
    }
}
?>