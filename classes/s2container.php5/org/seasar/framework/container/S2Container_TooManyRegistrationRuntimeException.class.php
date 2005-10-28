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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container
 * @author klove
 */
final class S2Container_TooManyRegistrationRuntimeException extends S2Container_S2RuntimeException {

    private $key_;
    private $componentClasses_;

    public function S2Container_TooManyRegistrationRuntimeException(
        $key,$componentClasses) {

        $args = array($key);
        foreach($componentClasses as $clazz){
            array_push($args,$clazz->getName());
        }
        parent::__construct("ESSR0045",$args);
        $this->componentClasses_ = $componentClasses;
    }
    
    public function getKey() {
        return $this->key_;
    }
    
    public function getComponentClasses() {
        return $this->componentClasses_;
    }

    private static function getClassNames($componentClasses) {
        $buf = "";
        for ($i = 0; $i < count($componentClasses); ++$i) {
            $buf .= $componentClasses[$i];
            $buf .= ", ";
        }
        return $buf;
    }
}
?>
