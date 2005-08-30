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
 * @package org.seasar.framework.beans
 * @author klove
 */
interface BeanDesc {

    public function getBeanClass();
    
    public function hasPropertyDesc($propertyName);

    public function getPropertyDesc($propertyName);
    
    public function getPropertyDescSize();
    
    public function hasField($fieldName);
    
    public function getField($fieldName);
    
    public function newInstance($args);
        
    public function getSuitableConstructor();
    
    public function invoke($target, $methodName,$args);
        
    public function getMethods($methodName);
    
    public function hasMethod($methodName);
        
    public function getMethodNames();

    public function hasConstant($constName);
    
    public function getConstant($constName);

}
?>
