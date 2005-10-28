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
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class S2Container_AutoConstructorAssembler
    extends S2Container_AbstractConstructorAssembler {

    public function S2Container_AutoConstructorAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    public function assemble(){
        $args = array();
        $refMethod = $this->getComponentDef()->getConcreteClass()->getConstructor();
        if($refMethod != null){
            $args = $this->getArgs($this->getComponentDef()->getConcreteClass()->getConstructor()->getParameters());
        }
        if($this->getComponentDef() != null and 
           $this->getComponentDef()->getAspectDefSize()>0){
            return S2Container_AopProxyUtil::getProxyObject($this->getComponentDef(),$args); 
        }                
        return S2Container_ConstructorUtil::newInstance($this->getComponentDef()->getConcreteClass(), $args);
    }
}
?>