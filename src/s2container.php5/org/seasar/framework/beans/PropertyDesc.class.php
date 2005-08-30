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
// $Id: PropertyDesc.class.php,v 1.1 2005/05/28 16:50:13 klove Exp $
/**
 * @package org.seasar.framework.beans
 * @author klove
 */
interface PropertyDesc {

    public function getPropertyName();

    public function getPropertyType();

    public function getReadMethod();

    public function setReadMethod($readMethod);
    
    public function hasReadMethod();

    public function getWriteMethod();

    public function setWriteMethod($writeMethod);
    
    public function hasWriteMethod();

    public function getValue($target);

    public function setValue($target,$value);
    
    public function convertIfNeed($value);
}
?>
