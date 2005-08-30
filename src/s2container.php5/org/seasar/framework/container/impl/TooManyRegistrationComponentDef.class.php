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
// $Id: TooManyRegistrationComponentDef.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * 1つのキーに複数のコンポーネントが登録された場合に使用されます。
 * 
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class TooManyRegistrationComponentDef extends SimpleComponentDef {

    private $key_;
    private $componentClasses_ = array();

    public function TooManyRegistrationComponentDef($key) {
        $this->key_ = $key;
    }

    public function addComponentClass($componentClass) {
        array_push($this->componentClasses_,$componentClass);
    }

    /**
     * @see ComponentDef::getComponent()
     */
    public function getComponent() {

        throw new TooManyRegistrationRuntimeException(
            $this->key_,
            $this->componentClasses_);
    }
}
?>