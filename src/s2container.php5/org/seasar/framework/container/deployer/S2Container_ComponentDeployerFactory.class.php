<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
final class S2Container_ComponentDeployerFactory
{
    /**
     * 
     */
    private function __construct()
    {
    }

    /**
     * @param S2Container_ComponentDef
     */
    public static function create(S2Container_ComponentDef $componentDef)
    {
        if (S2Container_InstanceModeUtil::isSingleton($componentDef->
                                                 getInstanceMode())) {
            return new S2Container_SingletonComponentDeployer($componentDef);
        } else if (S2Container_InstanceModeUtil::isPrototype($componentDef->
                                                 getInstanceMode())) {
            return new S2Container_PrototypeComponentDeployer($componentDef);
        } else if (S2Container_InstanceModeUtil::isRequest($componentDef->
                                                 getInstanceMode())) {
            return new S2Container_RequestComponentDeployer($componentDef);
        } else if (S2Container_InstanceModeUtil::isSession($componentDef->
                                                 getInstanceMode())) {
            return new S2Container_SessionComponentDeployer($componentDef);
        } else {
            return new S2Container_OuterComponentDeployer($componentDef);
        }
    }
}
?>
