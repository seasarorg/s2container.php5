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
class S2Container_ManualConstructorAssembler
    extends S2Container_AbstractConstructorAssembler {

    /**
     * @param S2Container_ComponentDef
     */
    public function S2Container_ManualConstructorAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    public function assemble(){
        $args = array();
        for ($i = 0; $i < $this->getComponentDef()->getArgDefSize(); ++$i) {
            try {
                $args[$i] = $this->getComponentDef()->getArgDef($i)->getValue();
            } catch (S2Container_ComponentNotFoundRuntimeException $cause) {
                throw new S2Container_IllegalConstructorRuntimeException(
                    $this->getComponentDef()->getComponentClass(),
                    $cause);
            }
        }
        $beanDesc =
            S2Container_BeanDescFactory::getBeanDesc($this->getComponentDef()->getConcreteClass());
        return $beanDesc->newInstance($args,$this->getComponentDef());
    }
}
?>