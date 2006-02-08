<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.util
 * @author klove
 */
final class S2Container_ClassUtil
{
    /**
     * 
     */
    private function __construct()
    {
    }
    
    /**
     * @param ReflectionClass
     * @return array class source
     */
    static function getClassSource($refClass)
    {
        if (!is_readable($refClass->getFileName())) {
            throw new S2Container_S2RuntimeException('ESSR1006',
                      array($refClass->getFileName()));
        }
        
        $ret = array();
        $lines = file($refClass->getFileName());
        $start = $refClass->getStartLine();
        $end   = $refClass->getEndLine();
        for ($i = $start - 1; $i < $end; $i++) {
            array_push($ret,$lines[$i]);
        }

        return $ret;
    }

    /**
     * @param ReflectionClass
     * @return array source
     */
    static function getSource($refClass)
    {
        if (!is_readable($refClass->getFileName())) {
            throw new S2Container_S2RuntimeException('ESSR1006',
                      array($refClass->getFileName()));
        }
        
        $ret = array();
        return file($refClass->getFileName());
    }

    /**
     * @param ReflectionClass
     * @param string method name
     */
    public static function getMethod(ReflectionClass $clazz,
                                     $methodName)
    {
        try {
            return $clazz->getMethod($methodName);
        } catch (ReflectionException $e) {
            throw new S2Container_NoSuchMethodRuntimeException($clazz,$methodName,$e);
        }
    }

    /**
     * @param ReflectionClass
     * @param string method name
    public static function hasMethod(ReflectionClass $clazz,
                                     $methodName)
    {
        //return $clazz->hasMethod(methodName); php ver 5.1

        try {
            $m = $clazz->getMethod($methodName);
            return true;
        } catch (ReflectionException $e) {
            return false;
        }
    }
     */

    /**
     * @param ReflectionClass
     */
    public static function getInterfaces(ReflectionClass $clazz)
    {
        $interfaces = $clazz->getInterfaces();
        if ($clazz->isInterface()) {
            array_push($interfaces,$clazz);
        }       
        
        return $interfaces;
    }
}
?>
