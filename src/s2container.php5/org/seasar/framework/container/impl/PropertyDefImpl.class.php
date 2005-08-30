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
// $Id: PropertyDefImpl.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class PropertyDefImpl extends ArgDefImpl implements PropertyDef {

    private $propertyName_;
    
    public function PropertyDefImpl($propertyName=null, $value=null) {
        parent::__construct($value);
        if($propertyName != null){
            $this->propertyName_ = $propertyName;
        }
    }

    /**
     * @see PropertyDef::getPropertyName()
     */
    public function getPropertyName() {
        return $this->propertyName_;
    }
}
?>