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
// $Id: UuSetPropertyDescImpl.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.beans.impl
 * @author klove
 */
final class UuSetPropertyDescImpl extends PropertyDescImpl {

    private $setterPropertyName_;
    
    public function UuSetPropertyDescImpl(
                          $propertyName,
                          $propertyType,
                          $readMethod,
                          $writeMethod,
                          BeanDesc $beanDesc) {
        parent::__construct(
                          $propertyName,
                          $propertyType,
                          $readMethod,
                          $writeMethod,
                          $beanDesc);
                    
    }
    
    public final function getSetterPropertyName() {
        return $this->setterPropertyName_;
    }
    
    public final function setSetterPropertyName($name) {
        $this->setterPropertyName_ = $name;
    }
    
    public function setValue($target,$value) {
        try {
            MethodUtil::invoke($this->writeMethod_,$target, array($this->setterPropertyName_,$value));
        } catch (Exception $t) {
            throw new IllegalPropertyRuntimeException(
                    $this->beanDesc_->getBeanClass(), $this->propertyName_, $t);
        }
    }    
}
?>