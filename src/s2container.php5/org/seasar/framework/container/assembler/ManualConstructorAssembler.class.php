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
final class ManualConstructorAssembler
    extends AbstractConstructorAssembler {

    /**
     * @param ComponentDef
     */
    public function ManualConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    public function assemble(){
        $args = array();
        for ($i = 0; $i < $this->getComponentDef()->getArgDefSize(); ++$i) {
            try {
                $args[$i] = $this->getComponentDef()->getArgDef($i)->getValue();
            } catch (ComponentNotFoundRuntimeException $cause) {
                throw new IllegalConstructorRuntimeException(
                    $this->getComponentDef()->getComponentClass(),
                    $cause);
            }
        }
        $beanDesc =
            BeanDescFactory::getBeanDesc($this->getComponentDef()->getConcreteClass());
        return $beanDesc->newInstance($args,$this->getComponentDef());
    }
}
?>