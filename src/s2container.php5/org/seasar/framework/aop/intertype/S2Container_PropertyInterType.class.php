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
 * @version test
 */
class S2Container_PropertyInterType extends S2Container_AbstractInterType {
    
    const SETTER_PREFIX = "set";
    const GETTER_PREFIX = "get";
    const NONE = 0;
    const READ = 1;
    const WRITE = 2;
    const READWRITE = 3;
    const STR_NONE = "none";
    const STR_READ = "read";
    const STR_WRITE = "write";
    const STR_READWRITE = "readwrite";

    private static $logger = null;
    private static $annotationHandler = null;
    private $trace;
    private $defaultPropertyType = self::READWRITE;
    private $methodNames = array();

    public static function staticConst(){
        self::$logger = S2Container_S2Logger::getLogger(__CLASS__);
        self::$annotationHandler = new S2Container_DefaultPropertyAnnotationHandler();
        self::setupAnnotationHandler();
    }

    protected static function valueOf($type) {
        $propertyType = self::NONE;
        if (self::STR_READ === $type) {
            $propertyType = self::READ;
        } else if (self::STR_WRITE === $type) {
            $propertyType = self::WRITE;
        } else if (self::STR_READWRITE === $type) {
            $propertyType = self::READWRITE;
        }
        return $propertyType;
    }

    private static function setupAnnotationHandler() {
    }

    public function setTrace($trace) {
        $this->trace = $trace;
    }

    public function setDefaultPropertyType($defaultPropertyType) {
        $this->defaultPropertyType = self::valueOf($defaultPropertyType);
    }

    public function introduce(ReflectionClass $targetClass, $enhancedClass) {
        parent::introduce($targetClass, $enhancedClass);

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug("[PropertyInterType] Introducing... " .
                                 $this->getTargetClass()->getName());
        }

        $this->methodNames = array();
        $this->methodNames = $this->getTargetMethodNames($this->targetClass);
        $defaultValue = self::$annotationHandler->getPropertyType($this->targetClass, $this->defaultPropertyType);
        $targetProperties = $this->getTargetProperties($this->targetClass);

        for ($iter = $targetProperties->getIterator(); $iter->valid(); $iter->next() ) {
            $prop = $iter->current();
            $property = self::$annotationHandler->getPropertyType($prop, $defaultValue);
            switch ($property) {
            case self::READ:
                $this->createGetter($this->targetClass, $prop);
                break;
            case self::WRITE:
                $this->createSetter($this->targetClass, $prop);
                break;
            case self::READWRITE:
                $this->createGetter($this->targetClass, $prop);
                $this->createSetter($this->targetClass, $prop);
                break;
            default:
                break;
            }
        }
    }

    private function createGetter(ReflectionClass $targetClass, ReflectionProperty $targetProperty) {
        $targetPropertyName = $targetProperty->getName();
        $methodName = self::GETTER_PREFIX . $this->createMethodName($targetPropertyName);
        if ($this->hasMethod($methodName)) {
            return;
        }

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug("[PropertyInterType] Creating getter "
                    . $targetClass->getName() . "::" . $methodName);
        }

        $src = "()";
        $src .= "{";
        if ($this->trace) {
            $src .= "org.seasar.framework.log.Logger logger =";
            $src .= "org.seasar.framework.log.Logger.getLogger(" . $this->class .");";
            $src .= "if(logger.isDebugEnabled()){";
            $src .= "logger.debug(\"CALL \" . get_class(\$this) . \"#";
            $src .= $methodName;
            $src .= "() : \" + \$this->";
            $src .= $targetPropertyName;
            $src .= ");}";
        }
        $src .= "return \$this->";
        $src .= $targetPropertyName;
        $src .= ";}";

        //$type = gettype($targetProperty->getValue($targetClass->newInstance()));
        $type = null;
        $this->addMethod($type, $methodName, $src);
    }

    private function createSetter(ReflectionClass $targetClass, ReflectionProperty $targetProperty) {
        $targetPropertyName = $targetProperty->getName();
        $methodName = self::SETTER_PREFIX . $this->createMethodName($targetPropertyName);
        if ($this->hasMethod($methodName)) {
            return;
        }

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug("[PropertyInterType] Creating setter "
                    . $targetClass->getName() . "::" . $methodName);
        }

        $src = "()";
        $src .= "{";
        if ($this->trace) {
            $src .= "org.seasar.framework.log.Logger logger =";
            $src .= "org.seasar.framework.log.Logger.getLogger(get_class(\$this);";
            $src .= "if(logger.isDebugEnabled()){";
            $src .= "logger.debug(\"CALL \" . get_class(\$this) . \"#";
            $src .= $methodName;
            $src .= "(\" . func_get_arg(0) . \")\");}";
        }
        $src .= "\$this->";
        $src .= $targetPropertyName;
        $src .= " = func_get_arg(0);}";

        //$type = gettype($targetProperty->getValue($this->targetClass->newInstance()));
        $type = null;
        $this->addMethod($type, $methodName, $src);
    }

    private function getTargetProperties(ReflectionClass $targetClass) {
        $targetProperties = new ArrayObject();

        $nominationProperties = $this->getProperties($targetClass);
        $c = count($nominationProperties);
        for ($i = 0; $i < $c; ++$i) {
            $prop = $nominationProperties[$i];
            $targetProperties->append($prop);
        }

        return $targetProperties;
    }

    private function getTargetMethodNames(ReflectionClass $targetClass){
        $meth = array();
        $methods = $targetClass->getMethods();
        foreach($methods as $method){
            $meth[] = $method->getName();
        }
        return $meth;
    }

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

    private function createMethodName($propName) {
        $methodName = ucfirst($propName);
        if (preg_match("/_$/", $methodName)) {
            $methodName = preg_replace("/(.+)(_$)/", "\\1", $methodName);
        }

        return $methodName;
    }

    private function hasMethod($methodName) {
        return in_array($methodName, $this->methodNames);
    }

}

S2Container_PropertyInterType::staticConst();

?>
