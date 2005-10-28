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
abstract class AbstractConstructorAssembler extends AbstractAssembler
        implements ConstructorAssembler {

    public function AbstractConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    protected function assembleDefault() {
        $clazz = $this->getComponentDef()->getConcreteClass();
        
        if($this->getComponentDef() != null and 
           $this->getComponentDef()->getAspectDefSize()>0){
            return AopProxyUtil::getProxyObject($this->getComponentDef(),$args); 
        }        
        return ConstructorUtil::newInstance($clazz, null);
    }
}
?>
