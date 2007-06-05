<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
class S2Container_AutoPropertyAssembler 
    extends S2Container_ManualPropertyAssembler
{
    private $log_;

    /**
     * @param S2Container_ComponentDef
     */    
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        parent::__construct($componentDef);
        $this->log_ = S2Container_S2Logger::getLogger(get_class($this));
    }

    /**
     * @see S2Container_PropertyAssembler::assemble()
     */
    public function assemble($component)
    {
        parent::assemble($component);
        $beanDesc = $this->getBeanDesc($component);
        $container = $this->getComponentDef()->getContainer();
        $o = $beanDesc->getPropertyDescSize();
        for ($i = 0; $i < $o; ++$i) {
            $value = null;
            $propDesc = $beanDesc->getPropertyDesc($i);
            $propName = $propDesc->getPropertyName();
            if (!$this->getComponentDef()->hasPropertyDef($propName) and
                $propDesc->getWriteMethod() != null and
                S2Container_AutoBindingUtil::isSuitable($propDesc->
                                                     getPropertyType())) {
                try {
                    $value = $container->getComponent($propDesc->
                                               getPropertyType()->getName());
                } catch (S2Container_ComponentNotFoundRuntimeException $ex) {
                    $this->log_->info($ex->getMessage() . ". skip property [{$this->getComponentDef()->getComponentClass()->getName()}::$propName]",
                                      __METHOD__);
                    continue;
                }
                $this->setValue($propDesc,$component,$value);
            }
        }
    }
}
?>
