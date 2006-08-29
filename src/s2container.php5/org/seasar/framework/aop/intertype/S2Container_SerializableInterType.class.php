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

    public function introduce(ReflectionClass $targetClass, $enhancedClass) {
        parent::introduce($targetClass, $enhancedClass);
        self::$logger = S2Container_S2Logger::getLogger(__CLASS__);

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[SerializableInterType] Introducing... ' .
                                 $this->getTargetClass()->getName());
        }
        
        if(!$this->targetClass->implementsInterface('Serializable')){
            $this->addInterface('Serializable');
        }

        $this->createSerializeMethod();
        $this->createUnserializeMethod();
    }

    private function createSerializeMethod() {
        $methodName = 'serialize';
        if($targetClass->hasMethod($methodName)){
            return;
        }
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[SerializableInterType] Creating Serialize Method ' .
                                $this->targetClass->getName());
        }

        $src = array();
        $src[] = '(){';
        $src[] = '    \$serial = array();';
        $src[] = '    foreach($this as $property => $value){';
        $src[] = '        $serial[$property] = $value;';
        $src[] = '    }';
        $src[] = '    return serialize($serial);';
        $src[] = '}';

        $type = array(self::PUBLIC_);
        $this->addMethod($type, $methodName, implode(PHP_EOL, $src));
    }

    private function createUnserializeMethod(ReflectionClass $targetClass) {
        $methodName = 'unserialize';
        if($targetClass->hasMethod($methodName)){
            return;
        }
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug('[SerializableInterType] Creating Unserialize Method ' .
                                $this->targetClass->getName());
        }

        $src = array();
        $src[] = '(\$serialized){';
        $src[] = '    $unserialize = unserialize($serialized);';
        $src[] = '    foreach($unserialize as $property => $value){';
        $src[] = '        $this->$property = $value;';
        $src[] = '    }';
        $src[] = '}';

        $type = array(self::PUBLIC_);
        $this->addMethod($type, $methodName, implode(PHP_EOL, $src));
    }

}

?>
