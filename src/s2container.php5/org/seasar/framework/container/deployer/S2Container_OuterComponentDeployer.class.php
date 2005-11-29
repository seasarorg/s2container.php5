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
class S2Container_OuterComponentDeployer
    extends S2Container_AbstractComponentDeployer
{
    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        parent::__construct($componentDef);
    }

    /**
     * @see S2Container_ComponentDeployer::deploy()
     */
    public function deploy()
    {
        throw new S2Container_UnsupportedOperationException("deploy");
    }
    
    /**
     * 
     */
    public function injectDependency($outerComponent)
    {
        $this->_checkComponentClass($outerComponent);
        $this->getPropertyAssembler()->assemble($outerComponent);
        $this->getInitMethodAssembler()->assemble($outerComponent);
    }

    /**
     * 
     */    
    private function _checkComponentClass($outerComponent)
    {
        $componentClass = $this->getComponentDef()->getComponentClass();
        if ($componentClass == null) {
            return;
        }

        if (!is_a($outerComponent,$componentClass->getName())) {
            throw new S2Container_ClassUnmatchRuntimeException($componentClass,
                                            new ReflectionClass($outerComponent));
        }
    }
    
    /**
     * @see S2Container_ComponentDeployer::init()
     */    
    public function init()
    {
    }
    
    /**
     * @see S2Container_ComponentDeployer::destroy()
     */    
    public function destroy()
    {
    }
}
?>
