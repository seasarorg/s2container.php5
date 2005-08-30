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
 * コンポーネントの循環参照が起きたときの実行時例外
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
class CyclicReferenceRuntimeException extends S2RuntimeException {

    private $componentClass_;
    
    public function CyclicReferenceRuntimeException(
        $componentClass) {
        parent::__construct("ESSR0047",array($componentClass->getName()));
            
        $this->componentClass_ = $componentClass;
    }
    
    public function getComponentClass() {
        return $this->componentClass_;
    }
}
?>
