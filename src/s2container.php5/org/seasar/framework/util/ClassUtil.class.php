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
final class ClassUtil {

    private function ClassUtil() {
    }
    
    /**
     * @param ReflectionClass
     * @return array class source
     */
    static function getClassSource($refClass){
    
        if(!is_readable($refClass->getFileName())){
            throw new S2RuntimeException('ESSR1006',array($refClass->getFileName()));
        }
        
        $ret = array();
        $lines = file($refClass->getFileName());
        $start = $refClass->getStartLine();
        $end   = $refClass->getEndLine();
        for($i=$start-1;$i<$end;$i++){
            array_push($ret,$lines[$i]);
        }

        return $ret;
    }

    /**
     * @param ReflectionClass
     * @return array source
     */
    static function getSource($refClass){
    
        if(!is_readable($refClass->getFileName())){
            throw new S2RuntimeException('ESSR1006',array($refClass->getFileName()));
        }
        
        $ret = array();
        return file($refClass->getFileName());
    }

	public static function getMethod(
		                          ReflectionClass $clazz,
		                          $methodName) {
        try{
            return $clazz->getMethod($methodName);
        }catch(ReflectionException $e){
            throw new NoSuchMethodRuntimeException($clazz,$methodName,$e);
        }
	}

	public static function hasMethod(
		                          ReflectionClass $clazz,
		                          $methodName) {
    	//return $clazz->hasMethod(methodName); php ver 5.1

        try{
            $m = $clazz->getMethod($methodName);
            return true;
        }catch(ReflectionException $e){
            return false;
        }
	}

	public static function getInterfaces(ReflectionClass $clazz){

        $interfaces = $clazz->getInterfaces();
        if($clazz->isInterface()){
            array_push($interfaces,$clazz);
        }       
        
        return $interfaces;
	}
}
?>