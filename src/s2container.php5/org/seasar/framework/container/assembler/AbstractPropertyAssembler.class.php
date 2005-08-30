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
abstract class AbstractPropertyAssembler
    extends AbstractAssembler
    implements PropertyAssembler {

    public function AbstractPropertyAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    protected function getValue(PropertyDef $propertyDef, $component) {
        try {
            return $propertyDef->getValue();
        } catch (ComponentNotFoundRuntimeException $cause) {
            throw new IllegalPropertyRuntimeException(
                $this->getComponentClass($component),
                $propertyDef->getPropertyName(),
                $cause);
        }
    }

    protected function setValue(
        PropertyDesc $propertyDesc,
        $component,
        $value){
        
        if ($value == null) {
            return;
        }
        try {
            $propertyDesc->setValue($component,$value);
        } catch (Exception $ex) {
            throw new IllegalPropertyRuntimeException(
                $this->getComponentDef()->getComponentClass(),
                $propertyDesc->getPropertyName(),
                $ex);
        }
    }
}
?>
