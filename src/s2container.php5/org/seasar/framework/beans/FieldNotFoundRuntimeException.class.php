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
 * クラスのpublic staticなフィールドが見つからなかったときの実行時例外
 * 
 * @package org.seasar.framework.beans
 * @author klove
 */
class FieldNotFoundRuntimeException
    extends S2RuntimeException {

    private $targetClass_;
    private $fieldName_;

    public function FieldNotFoundRuntimeException(
        $componentClass,
        $fieldName) {
        parent::__construct(
            "ESSR0070",array($componentClass->getName(),$fieldName));
            
        $this->targetClass_ = $componentClass;
        $this->fieldName_ = $fieldName;
    }

    public function getTargetClass() {
        return $this->targetClass_;
    }
    
    public function getFieldName() {
        return $this->fieldName_;
    }
}
?>
