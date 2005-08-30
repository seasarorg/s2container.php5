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
// $Id: MetaDefSupport.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * MetaDefの設定をサポートします。
 * 
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class MetaDefSupport {

    private $metaDefs_ = array();

    private $container_;

    public function MetaDefSupport($container=null) {
        if($container instanceof S2Container){
            $this->setContainer($container);
        }
    }

    public function addMetaDef(MetaDef $metaDef) {
        if ($this->container_ != null) {
            $metaDef->setContainer($this->container_);
        }
        array_push($this->metaDefs_,$metaDef);
    }

    public function getMetaDefSize() {
        return count($this->metaDefs_);
    }

    public function getMetaDef($name) {
        if(is_int($name)){
            return $this->metaDefs_[$name];
        }
        
        for ($i = 0; $i < $this->getMetaDefSize(); ++$i) {
            $metaDef = $this->getMetaDef($i);
            if ($name == null && $metaDef->getName() == null || $name != null
                    && strtolower($name) == strtolower($metaDef->getName())) {
                return $metaDef;
            }
        }
        return null;
    }

    public function getMetaDefs($name) {
        $defs = array();
        for ($i = 0; $i < $this->getMetaDefSize(); ++$i) {
            $metaDef = $this->getMetaDef($i);
            if ($name == null && $metaDef->getName() == null || $name != null
                    && strtolower($name) == strtolower($metaDef->getName())) {
                array_push($defs,$metaDef);
            }
        }
        return $defs;
    }

    public function setContainer(S2Container $container) {
        $this->container_ = $container;
        for ($i = 0; $i < $this->getMetaDefSize(); ++$i) {
            $this->getMetaDef($i)->setContainer($container);
        }
    }
}
?>