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
// $Id: PropertyNotFoundRuntimeException.class.php,v 1.1 2005/05/28 16:50:13 klove Exp $
/**
 * クラスのプロパティが見つからなかったときの実行時例外
 * 
 * @package org.seasar.framework.beans
 * @author klove
 */
class PropertyNotFoundRuntimeException
    extends S2RuntimeException {

    private $targetClass_;
    private $propertyName_;

    public function PropertyNotFoundRuntimeException(
        $componentClass,
        $propertyName) {
        parent::__construct("ESSR0065",array($componentClass->getName(), $propertyName));
        $this->targetClass_ = $componentClass;
        $this->propertyName_ = $propertyName;
    }

    public function getTargetClass() {
        return $this->targetClass_;
    }
    
    public function getPropertyName() {
        return $this->propertyName_;
    }
}
?>
