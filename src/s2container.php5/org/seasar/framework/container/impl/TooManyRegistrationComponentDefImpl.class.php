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
 * 1つのキーに複数のコンポーネントが登録された場合に使用されます。
 * 
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class TooManyRegistrationComponentDefImpl extends SimpleComponentDef
                          implements TooManyRegistrationComponentDef{

    private $key_;
    private $componentDefs_ = array();

    public function TooManyRegistrationComponentDefImpl($key) {
        $this->key_ = $key;
    }

    public function addComponentDef($componentDef) {
        array_push($this->componentDefs_,$componentDef);
    }

    public function getComponentDefSize() {
        return count($this->componentDefs_);
    }
    
    /**
     * @see ComponentDef::getComponent()
     */
    public function getComponent() {

        throw new TooManyRegistrationRuntimeException(
            $this->key_,
            $this->getComponentClasses());
    }

    public function getComponentDefs() {
        return $this->componentDefs_;
    }

    public function getComponentClasses() {
        $classes = array();
        $size = $this->getComponentDefSize();
        foreach($this->componentDefs_ as $componentDef) {
            array_push($classes,$componentDef->getComponentClass());
        }
        return $classes;
    }
}
?>