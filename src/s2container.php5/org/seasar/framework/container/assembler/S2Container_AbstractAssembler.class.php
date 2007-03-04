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
abstract class S2Container_AbstractAssembler
{
    private $log_;
    
    private $componentDef_;

    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        $this->componentDef_ = $componentDef;
        $this->log_ = S2Container_S2Logger::getLogger(get_class($this));
    }

    /**
     * @return S2Container_ComponentDef
     */
    protected final function getComponentDef()
    {
        return $this->componentDef_;
    }

    /**
     * @param object
     */
    protected final function getBeanDesc($component = null)
    {
        if (!is_object($component)) {
            return S2Container_BeanDescFactory::getBeanDesc($this->
                getComponentDef()->getComponentClass());
        }
        
        return S2Container_BeanDescFactory::getBeanDesc($this->
                                 getComponentClass($component));
    }
    
    /**
     * @param object
     */
    protected final function getComponentClass($component)
    {
        $clazz = $this->componentDef_->getComponentClass();
        if ($clazz != null) {
            return $clazz;
        } else {
            return new ReflectionClass($component);
        }
    }

    /**
     * @param ReflectionParameter[] 
     */    
    protected function getArgs($argTypes)
    {
        $args = array();
        $o = count($argTypes);
        for ($i = 0; $i < $o; ++$i) {
            try {
                if ($argTypes[$i]->getClass() != null &&
                    S2Container_AutoBindingUtil::isSuitable($argTypes[$i]->getClass())) {
                    $args[$i] = $this->getComponentDef()->getContainer()->
                        getComponent($argTypes[$i]->getClass()->getName());
                } else {
                    if ($argTypes[$i]->isOptional()) {
                        $args[$i] = $argTypes[$i]->getDefaultValue();
                    } else {
                        $args[$i] = null;
                    }
                }
            } catch (S2Container_ComponentNotFoundRuntimeException $ex) {
                $this->log_->info($ex->getMessage(),__METHOD__);
                $args[$i] = null;
            }
        }
        return $args;
    }
}
?>
