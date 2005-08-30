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
// $Id: MetaDefImpl.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class MetaDefImpl extends ArgDefImpl implements MetaDef {

    private $name_;
    
    public function MetaDefImpl($name=null,$value=null) {
        parent::__construct($value);
        
        if($name != null){
            $this->name_ = $name;
        }
    }

    /**
     * @see MetaDef::getName()
     */    
    public function getName() {
        return $this->name_;
    }
}
?>