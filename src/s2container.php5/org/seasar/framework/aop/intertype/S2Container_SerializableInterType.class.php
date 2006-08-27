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
class S2Container_SerializableInterType extends S2Container_AbstractInterType {
    
    private static $logger = null;

    public function introduce($arg1, $arg2) {
        parent::introduce($arg1, $arg2);
        self::$logger = S2Container_S2Logger::getLogger(__CLASS__);

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[SerializableInterType] Introducing... ' .
                                 $this->getTargetClass()->getName());
        }
        
        if(!$this->targetClass->implementsInterface('Serializable')){
            return ;
        }

        $this->createSerializeMethod($this->targetClass);
        $this->createUnserializeMethod($this->targetClass);
    }

    private function createSerializeMethod(ReflectionClass $targetClass) {
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[SerializableInterType] Creating Serialize Method ' .
                                $targetClass->getName());
        }

        $src = "()";
        $src .= "{";
        $src .= "\$serial = array();";
        $src .= "foreach(\$this as \$property){";
        $src .= "   \$serial[\$property] = \$this->\$property;";
        $src .= "}";
        $src .= "return serialize(\$serial);";
        $src .= "}";

        $methodName = "serialize";
        //$type = gettype($targetProperty->getValue($targetClass->newInstance()));
        $type = null;
        $this->addMethod($type, $methodName, $src);
    }

    private function createUnserializeMethod(ReflectionClass $targetClass) {
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[SerializableInterType] Creating Serialize Method ' .
                                $targetClass->getName());
        }

        $src = "(\$serialized)";
        $src .= "{";
        $src .= "\$unserialize = unserialize(\$serialized);";
        $src .= "foreach(\$unserialize as \$property => $value){";
        $src .= "   \$this->\$property = \$value;";
        $src .= "}";

        $methodName = "unserialize";
        //$type = gettype($targetProperty->getValue($targetClass->newInstance()));
        $type = null;
        $this->addMethod($type, $methodName, $src);
    }

    private function getTargetMethodNames(ReflectionClass $targetClass){
        $meth = array();
        $methods = $targetClass->getMethods();
        foreach($methods as $method){
            $meth[] = $method->getName();
        }
        return $meth;
    }

}

?>
