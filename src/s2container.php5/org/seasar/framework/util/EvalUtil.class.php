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
 * @package org.seasar.framework.util
 * @author klove
 */
final class EvalUtil {

    private function EvalUtil() {
    }
  
    /**
     * @param string eval expression
     * @return string eval expression
     */  
    public static function getExpression($expression){
        $exp = $expression;
        if(!preg_match("/\sreturn\s/",$exp) and 
           !preg_match("/\nreturn\s/",$exp) and
           !preg_match("/^return\s/",$exp)){
            $exp = "return " . $exp;
        }
        if(!preg_match("/;$/",$exp)){
            $exp = $exp . ";";
        }

        return $exp;
    } 

    /**
     * @param string eval expression
     * @return string eval expression
     */  
    public static function addSemiColon($expression){
        $exp = trim($expression);

        if(!preg_match("/;$/",$exp)){
            $exp = $exp . ";";
        }

        return $exp;
    } 
}
?>