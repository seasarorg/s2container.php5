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
class S2Container_InterTypeChain implements S2Container_InterType {
    
    /** */
    protected $interTypes = array();

    /**
     * 
     */
    public function __construct() {
    }

    /**
     * 
     */
    public function add(S2Container_InterType $interType) {
        $this->interTypes[] = $interType;
    }

    /**
     * 
     */
    public function introduce(ReflectionClass $targetClass, $enhancedClass) {
        $c = count($this->interTypes);
        for ($i = 0; $i < $c; ++$i) {
            $this->interTypes[$i]->introduce($targetClass, $enhancedClass);
        }
    }
}
?>
