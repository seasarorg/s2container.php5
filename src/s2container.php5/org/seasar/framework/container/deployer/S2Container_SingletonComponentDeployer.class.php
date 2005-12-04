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
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
class S2Container_SingletonComponentDeployer
    extends S2Container_AbstractComponentDeployer
 {
    private $component_;
    private $instantiating_ = false;

    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        parent::__construct($componentDef);
    }

    /**
     * 
     */
    public function deploy()
    {
        if ($this->component_ == null) {
            $this->_assemble();
        }
        return $this->component_;
    }
    
    /**
     * 
     */
    public function injectDependency($component)
    {
        throw new S2Container_UnsupportedOperationException("injectDependency");
    }

    /**
     * 
     */
    private function _assemble()
    {
        if ($this->instantiating_) {
            throw new S2Container_CyclicReferenceRuntimeException($this->
                                  getComponentDef()->getComponentClass());
        }
        $this->instantiating_ = true;
        $this->component_ = $this->getConstructorAssembler()->assemble();
        $this->instantiating_ = false;
        $this->getPropertyAssembler()->assemble($this->component_);
        $this->getInitMethodAssembler()->assemble($this->component_);
    }
    
    /**
     * @see S2Container_ComponentDeployer::init()
     */
    public function init()
    {
        $this->deploy();
    }
  
    /**
     * @see S2Container_ComponentDeployer::destroy()
     */  
    public function destroy()
    {
        if ($this->component_ == null) {
            return;
        }
        $this->getDestroyMethodAssembler()->assemble($this->component_);
        $this->component_ = null;
    }
}
?>
