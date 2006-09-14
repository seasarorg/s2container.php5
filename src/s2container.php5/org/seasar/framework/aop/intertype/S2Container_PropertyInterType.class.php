<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.aop.intertype
 * @author nowel
 */
class S2Container_PropertyInterType extends S2Container_AbstractInterType {
    
    /** */
    const SETTER_PREFIX = 'set';
    /** */
    const GETTER_PREFIX = 'get';
    /** */
    const READ = 'read';
    /** */
    const WRITE = 'write';
    /** */
    const READWRITE = 'readwrite';

    /** */
    private static $logger = null;
    /** */
    private static $annotationHandler = null;
    /** */
    private $trace = false;
    /** */
    private $defaultPropertyType = self::READWRITE;

    /**
     * 
     */
    protected static function initialize(){
        self::$logger = S2Container_S2Logger::getLogger(__CLASS__);
        self::$annotationHandler = new S2Container_DefaultPropertyAnnotationHandler();
        self::setupAnnotationHandler();
    }

    /**
     * 
     */
    protected static function setupAnnotationHandler() {
    }

    /**
     * 
     */
    public function setTrace($trace) {
        $this->trace = $trace;
    }

    /**
     * 
     */
    public function setDefaultPropertyType($defaultPropertyType) {
        $this->defaultPropertyType = $defaultPropertyType;
    }

    /**
     * 
     */
    public function introduce(ReflectionClass $targetClass, $enhancedClass) {
        parent::introduce($targetClass, $enhancedClass);
        self::initialize();

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[PropertyInterType] Introducing... ' .
                                 $this->getTargetClass()->getName());
        }

        //$defaultValue = self::$annotationHandler->getPropertyType($this->targetClass, $this->defaultPropertyType);
        $targetProperties = $this->getTargetProperties($this->targetClass);

        foreach($targetProperties as $property){
            $this->createGetter($this->targetClass, $property);
            $this->createSetter($this->targetClass, $property);
        }
    }

    /**
     * 
     */
    private function createGetter(ReflectionClass $targetClass, ReflectionProperty $targetProperty) {
        $targetPropertyName = $targetProperty->getName();
        $methodName = self::GETTER_PREFIX . $this->createMethodName($targetPropertyName);
        if ($targetClass->hasMethod($methodName)) {
            return;
        }

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[PropertyInterType] Creating getter ' .
                                 $targetClass->getName() . '::' . $methodName);
        }

        $src = array();
        $src[] = '(){';
        if ($this->trace) {
            $className = $targetClass->getName();
            $buf = '';
            $buf .= 'S2Container_S2Logger::getLogger("' . $className . '")->';
            $buf .= 'debug("CALL " . "' . $className . '" . "::" . ';
            $buf .= '"' . $methodName . '()");';
            $src[] = $buf;
        }
        $src[] = 'return $this->' . $targetPropertyName . ';}';

        $type = array(self::PUBLIC_);
        $this->addMethod($type, $methodName, implode(PHP_EOL, $src));
    }

    /**
     * 
     */
    private function createSetter(ReflectionClass $targetClass, ReflectionProperty $targetProperty) {
        $targetPropertyName = $targetProperty->getName();
        $methodName = self::SETTER_PREFIX . $this->createMethodName($targetPropertyName);
        if ($targetClass->hasMethod($methodName)) {
            return;
        }

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[PropertyInterType] Creating setter ' . 
                                 $targetClass->getName() . '::' . $methodName);
        }

        $src = array();
        $src[] = '($value){';
        if ($this->trace) {
            $className = $targetClass->getName();
            $buf = '';
            $buf .= 'S2Container_S2Logger::getLogger("' . $className . '")->';
            $buf .= 'debug("CALL " . "' . $className . '" . "::" . ';
            $buf .= '"' . $methodName . '(" . $value . ")");';
            $src[] = $buf;
        }
        $src[] = '$this->' . $targetPropertyName . ' = $value;}';

        $type = array(self::PUBLIC_);
        $this->addMethod($type, $methodName, implode(PHP_EOL, $src));
    }

    /**
     * 
     */
    private function getTargetProperties(ReflectionClass $targetClass) {
        return $this->getProperties($targetClass);
    }

    /**
     * 
     */
    private function getProperties(ReflectionClass $targetClass) {
        $parentClass = $targetClass->getParentClass();
        
        $parentProperties = array();
        if ($parentClass !== false) {
            $parentProperties = $this->getProperties($parentClass);
        }

        $currentProperties = $targetClass->getProperties();
        $properties = array();
        $properties = array_merge($parentProperties, $properties);
        $properties = array_merge($currentProperties, $properties);
        return $properties;
    }

    /**
     * 
     */
    private function createMethodName($propName) {
        $methodName = ucfirst($propName);
        if (preg_match('/_$/', $methodName)) {
            $methodName = preg_replace('/(.+)(_$)/', "\\1", $methodName);
        }
        return $methodName;
    }

}

?>
