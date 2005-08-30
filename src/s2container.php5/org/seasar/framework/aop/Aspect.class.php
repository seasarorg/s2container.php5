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
// $Id: Aspect.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * プログラムに適用する関心を定義します。
 * 
 * AroundAdviceとPointcutで構成されます。
 *
 * @package org.seasar.framework.aop
 * @author klove
 */
interface Aspect {
    
    public function getMethodInterceptor();
    
    public function getPointcut();
    
    public function setPointcut(Pointcut $pointcut);

}
?>
