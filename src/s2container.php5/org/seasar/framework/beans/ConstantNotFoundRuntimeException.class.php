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
 * クラスの const が見つからなかったときの実行時例外
 * 
 * @package org.seasar.framework.beans
 * @author klove
 */
class ConstantNotFoundRuntimeException
    extends S2RuntimeException {

    private $targetClass_;
    private $constantName_;

    public function ConstantNotFoundRuntimeException(
        $componentClass,
        $constantName) {
        parent::__construct(
            "ESSR1007",array($componentClass->getName(),$constantName));
            
        $this->targetClass_ = $componentClass;
        $this->constantName_ = $constantName;
    }

    public function getTargetClass() {
        return $this->targetClass_;
    }
    
    public function getConstantName() {
        return $this->constantName_;
    }
}
?>
