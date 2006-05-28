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
 * @package org.seasar.extension.annotation.container
 * @author klove
 */
class S2Container_AnnotationContainer
{
    public static $DEFAULT_ANNOTATION_READER = "S2Container_CommentAnnotationReader";
    private static $container = null;
    private $annotationMap = array();
    private $annotationReader = null;

    /**
     * 
     */
    private function __construct()
    {
    }

    /**
     * 
     */    
    public static function getInstance()
    {
        if (self::$container == null) {
            self::$container = new S2Container_AnnotationContainer();
            if (defined('S2CONTAINER_ANNOTATION_READER')) {
                $reader = S2CONTAINER_ANNOTATION_READER;
            } else {
                $reader = self::$DEFAULT_ANNOTATION_READER;
            }
            
            self::$container->setAnnotationReader(new $reader());
        }    
        return self::$container;
    }
    
    /**
     * 
     */    
    public function setAnnotationReader(S2Container_AnnotationReader $reader)
    {
        $this->annotationReader = $reader;
    }
    
    /**
     * 
     */
    public function getAnnotations($classKey,
                                   $methodName = null,
                                   $srcFile = null)
    {
        if ($classKey instanceof ReflectionClass) {
            $className = $classKey->getName();
            $clazz = $classKey;
        } else {
            $className = $classKey;
            $clazz = new ReflectionClass($classKey);
        }

        $annotationId = $this->getAnnotationId($className,$methodName);
        if (array_key_exists($annotationId,$this->annotationMap)) {
            return $this->annotationMap[$annotationId];
        }

        $annotations = $this->annotationReader->
                         getAnnotations($clazz,$methodName);
        if (is_array($annotations)) {
            $this->annotationMap[$annotationId] = $annotations;
             return $annotations;
        }

        return null;
    }

    /**
     * 
     */
    public function getAnnotation($annotationType,
                                  $className,
                                  $methodName = null,
                                  $srcFile = null)
    {
        $annotations = $this->getAnnotations($className,$methodName,$srcFile);
        $annotationId = $this->getAnnotationId($className,$methodName);

        if (!is_array($annotations) or 
            !array_key_exists($annotationType,$annotations)) {
            throw new S2Container_AnnotationRuntimeException('ERR002',
                          array($annotationType,$className,$methodName));
    }
        return $annotations[$annotationType];
    }

    /**
     * 
     */
    public function isAnnotationPresent($annotationType,
                                        $className,
                                        $methodName = null,
                                        $srcFile = null)
    {
        $annotations = $this->getAnnotations($className,$methodName,$srcFile);
        $annotationId = $this->getAnnotationId($className,$methodName);

        if (!is_array($annotations) or 
            !array_key_exists($annotationType,$annotations)) {
            return false;
        }
        return true;
    }    

    /**
     * 
     */
    public function getAnnotationId($className,$methodName)
    {
        if ($methodName == null) {
            return $className . ":class";
        } else {
            return $className . ":" . $methodName;
        }
    }
}
?>
