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
// $Id: AspectDefSupport.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * AspectDefの設定をサポートします。
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class AspectDefSupport {

    private $aspectDefs_ = array();
    private $container_;

    public function AspectDefSupport() {
    }

    public function addAspectDef(AspectDef $aspectDef) {
        if ($this->container_ != null) {
            $aspectDef->setContainer($this->container_);
        }
        array_push($this->aspectDefs_,$aspectDef);
    }

    public function getAspectDefSize() {
        return count($this->aspectDefs_);
    }

    public function getAspectDef($index) {
        return $this->aspectDefs_[$index];
    }

    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getAspectDefSize(); ++$i) {
            $this->getAspectDef($i)->setContainer($container);
        }
    }
}
?>