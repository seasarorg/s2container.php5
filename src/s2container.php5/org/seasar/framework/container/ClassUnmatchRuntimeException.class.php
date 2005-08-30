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
 * 定義されたコンポーネントのクラスと実際のコンポーネントのクラスが異なるときの実行時例外
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class ClassUnmatchRuntimeException extends S2RuntimeException {

    private $componentClass_;
    private $realComponentClass_;
    
    public function ClassUnmatchRuntimeException(
        $componentClass,
        $realComponentClass) {
        parent::__construct("ESSR0069", 
            array(
            $componentClass != null ? $componentClass->getName() : "null",
            $realComponentClass != null ? $realComponentClass->getName() : "null"));
            
        $this->componentClass_ = $componentClass;
        $this->realComponentClass_ = $realComponentClass;
    }
    
    public function getComponentClass() {
        return $ths->componentClass_;
    }
    
    public function getRealComponentClass() {
        return $this->realComponentClass_;
    }
}
?>
