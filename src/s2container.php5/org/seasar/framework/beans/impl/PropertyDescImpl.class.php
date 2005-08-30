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
 * @package org.seasar.framework.beans.impl
 * @author klove
 */
class PropertyDescImpl implements PropertyDesc {

    protected $propertyName_ = null;
    protected $propertyType_ = null;
    protected $readMethod_ = null;
    protected $writeMethod_ = null;
    protected $beanDesc_ = null;
    
    public function PropertyDescImpl($propertyName=null,
                                       $propertyType,
                                       $readMethod,
                                       $writeMethod,
                                       BeanDesc $beanDesc) {
                
        if ($propertyName == null) {
            throw new EmptyRuntimeException("propertyName");
        }
        
        $this->propertyName_ = $propertyName;
        $this->propertyType_ = $propertyType;
        $this->readMethod_ = $readMethod;
        $this->writeMethod_ = $writeMethod;
        $this->beanDesc_ = $beanDesc;
    }
    
    public final function getPropertyName() {
        return $this->propertyName_;
    }

    public final function getPropertyType() {
        return $this->propertyType_;
    }
    
    public final function getReadMethod() {
        return $this->readMethod_;
    }
    
    public final function setReadMethod($readMethod) {
        $this->readMethod_ = $readMethod;
    }
    
    public final function hasReadMethod() {
        return $this->readMethod_ != null;
    }
    
    public final function getWriteMethod() {
        return $this->writeMethod_;
    }
    
    public final function setWriteMethod($writeMethod) {
        $this->writeMethod_ = $writeMethod;
    }
    
    public final function hasWriteMethod() {
        return $this->writeMethod_ != null;
    }
    
    public final function getValue($target) {
        return MethodUtil::invoke($this->readMethod_,$target, null);
    }
    
    public function setValue($target,$value) {
        try {
            MethodUtil::invoke($this->writeMethod_,$target, $value);
        } catch (Exception $t) {
            throw new IllegalPropertyRuntimeException(
                    $this->beanDesc_->getBeanClass(), $this->propertyName_, $t);
        }
    }
    
    public final function getBeanDesc() {
        return $this->beanDesc_;
    }
    
    public final function __toString() {
        $buf = "";
        $buf .= "propertyName=";
        $buf .= $this->propertyName_;
        $buf .= ",propertyType=";
        $buf .= $this->propertyType_ != null ? $this->propertyType_->getName() : "null";
        $buf .= ",readMethod=";
        $buf .= $this->readMethod_ != null ? $this->readMethod_->getName() : "null";
        $buf .= ",writeMethod=";
        $buf .= $this->writeMethod_ != null ? $this->writeMethod_->getName() : "null";
        return $buf;
    }
    
    public function convertIfNeed($arg) {
    }
}
?>
