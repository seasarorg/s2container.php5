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
// $Id: InitMethodDefSupport.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * InitMethodDefの設定をサポートします。
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class InitMethodDefSupport {

    private $methodDefs_ = array();
    private $container_;

    public function InitMethodDefSupport() {
    }

    public function addInitMethodDef(InitMethodDef $methodDef) {
        if ($this->container_ != null) {
            $methodDef->setContainer($this->container_);
        }
        array_push($this->methodDefs_,$methodDef);
    }

    public function getInitMethodDefSize() {
        return count($this->methodDefs_);
    }

    public function getInitMethodDef($index) {
        return $this->methodDefs_[$index];
    }

    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0;$i < $this->getInitMethodDefSize(); ++$i) {
            $this->getInitMethodDef($i)->setContainer($container);
        }
    }
}
?>