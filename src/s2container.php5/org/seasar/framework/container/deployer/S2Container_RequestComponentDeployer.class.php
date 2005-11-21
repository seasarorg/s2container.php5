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
class S2Container_RequestComponentDeployer extends S2Container_AbstractComponentDeployer {

    private static $logger_;

    /**
     * @param S2Container_ComponentDef
     */
    public function S2Container_RequestComponentDeployer(S2Container_ComponentDef $componentDef) {
        parent::__construct($componentDef);
        $this->logger_ = S2Container_S2Logger::getLogger(get_class($this));
    }

    public function deploy() {
        $cd = $this->getComponentDef();
        $className = $cd->getComponentClass()->getName();

        $componentName = $cd->getComponentName();
        if ($componentName == null) {
            $componentName = $className;
        }
        $component = null;
        if(array_key_exists($componentName,$_REQUEST)){
            $component = $_REQUEST[$componentName];
        }
        if ($component != null){
            if($component instanceof $className) {
                return $component;
            }else{
                $this->logger_->warn(
                    S2ContainerMessageUtil::getMessageWithArgs(
                        'ESSR1005',
                        array('Request',$componentName,$className)),
                        __METHOD__);
            }
        }

        $component = $this->getConstructorAssembler()->assemble();
        $_REQUEST[$componentName] = $component;
        $this->getPropertyAssembler()->assemble($component);
        $this->getInitMethodAssembler()->assemble($component);
        return $component;
    }

    public function injectDependency($component) {
        throw new S2Container_UnsupportedOperationException("injectDependency");
    }

    /**
     * @see S2Container_ComponentDeployer::init()
     */
    public function init() {
    }

    /**
     * @see S2Container_ComponentDeployer::destroy()
     */
    public function destroy() {
    }
}
?>
