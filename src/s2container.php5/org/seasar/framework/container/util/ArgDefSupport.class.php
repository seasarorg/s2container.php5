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
// $Id: ArgDefSupport.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * ArgDefの設定をサポートします。
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class ArgDefSupport {

    private $argDefs_ = array();
    private $container_;
    
    public function ArgDefSupport() {
    }

    public function addArgDef(ArgDef $argDef) {
        if ($this->container_ != null) {
            $argDef->setContainer($this->container_);
        }
        array_push($this->argDefs_,$argDef);
    }
    
    public function getArgDefSize() {
        return count($this->argDefs_);
    }
    
    public function getArgDef($index) {
        return $this->argDefs_[$index];
    }
    
    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for($i=0;$i<$this->getArgDefSize();$i++) {
            $this->getArgDef($i)->setContainer($container);
        }
    }
}
?>