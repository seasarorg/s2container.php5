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
class S2Container_ManualPropertyAssembler extends S2Container_AbstractPropertyAssembler {

    /**
     * @param S2Container_ComponentDef
     */
    public function S2Container_ManualPropertyAssembler(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see S2Container_PropertyAssembler::assemble()
     */
    public function assemble($component) {
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getPropertyDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $propDef = $this->getComponentDef()->getPropertyDef($i);
            $value = $this->getValue($propDef, $component);
            try{
                $propDesc =
                    $beanDesc->getPropertyDesc($propDef->getPropertyName());
            }catch(S2Container_PropertyNotFoundRuntimeException $e1){
                try{
                	$propDesc =
                        $beanDesc->getPropertyDesc('__set');
                    $propDesc->setSetterPropertyName($propDef->getPropertyName());    
                }catch(S2Container_PropertyNotFoundRuntimeException $e2){
                    throw $e1;
                }
            }

            if(!$propDesc->hasWriteMethod()){
            	$propDesc =
                    $beanDesc->getPropertyDesc('__set');
                $propDesc->setSetterPropertyName($propDef->getPropertyName());    
            }    
            $this->setValue($propDesc,$component,$value);
        }
    }
}
?>