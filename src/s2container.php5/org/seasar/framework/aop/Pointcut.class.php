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
 * プログラム中のどこに特定のコード(Advice)を挿入するのかを定義します。
 *
 * @package org.seasar.framework.aop
 * @author klove
 */
interface Pointcut { 
    
    /**
     * このメソッドがtrueを返したメソッドに対してAdviceが適用されます。
     *
     * @param string Method Name
     * @return boolean Adviceが適用されるかどうかを返します。
     */
    public function isApplied($methodName);
}
?>
