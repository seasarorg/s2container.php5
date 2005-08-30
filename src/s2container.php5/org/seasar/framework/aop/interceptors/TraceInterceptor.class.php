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
// $Id: TraceInterceptor.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.aop.interceptors
 * @author klove
 */
class TraceInterceptor extends AbstractInterceptor {

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(MethodInvocation $invocation){
        $buf = "";
        $buf .= $this->getTargetClass($invocation)->getName();
        $buf .= "#";
        $buf .= $invocation->getMethod()->getName();
        $buf .= "(";
        $args = $invocation->getArguments(); 
        if ($args != null && count($args) > 0) { 
            $buf .= implode(',',$args);
        }
        $buf .= ")";
        $ret = null;
        $cause = null;
        print "BEGIN " . $buf ."\n";
        try {
            $ret = $invocation->proceed();
            $buf .= " : ";
            $buf .= $ret;
        } catch (Exception $t) {
            $buf .= " Exception:";
            $buf .= $t;
            $cause = $t;
        }
        print "END   " . $buf ."\n";
        if ($cause == null) {
            return $ret;
        } else {
            throw $cause;
        }
    }
}
?>
