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
 * メソッドを定義します。
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface MethodDef extends ArgDefAware {
    
    public function getMethodName();
    
    public function getArgs();
    
    public function getContainer();
    
    public function setContainer(S2Container $container);
    
    public function getExpression();
    
    public function setExpression($expression);
}
?>
