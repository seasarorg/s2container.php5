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
 * PropertyDefの設定をサポートします。
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class PropertyDefSupport {

    private $propertyDefs_ = array();
    private $propertyDefList_ = array();
    private $container_;

    public function PropertyDefSupport() {
    }

    public function addPropertyDef(PropertyDef $propertyDef) {
        if ($this->container_ != null) {
            $propertyDef->setContainer($this->container_);
        }
        $this->propertyDefs_[$propertyDef->getPropertyName()] = $propertyDef;
        array_push($this->propertyDefList_,$propertyDef->getPropertyName());
    }

    public function getPropertyDefSize() {
        return count($this->propertyDefs_);
    }

    public function getPropertyDef($propertyName) {
        if(is_int($propertyName)){
            return $this->propertyDefs_[$this->propertyDefList_[$propertyName]];
        }
        return $this->propertyDefs_[$propertyName];
    }
    
    public function hasPropertyDef($propertyName) {
        return array_key_exists($propertyName,$this->propertyDefs_);
    }

    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getPropertyDefSize(); ++$i) {
            $this->getPropertyDef($i)->setContainer($container);
        }
    }
}
?>