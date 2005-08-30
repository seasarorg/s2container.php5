<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
final class ComponentDeployerFactory {

    private function ComponentDeployerFactory() {
    }

    public static function create(ComponentDef $componentDef) {
        if (InstanceModeUtil::isSingleton($componentDef->getInstanceMode())) {
            return new SingletonComponentDeployer($componentDef);
        } else if (InstanceModeUtil::isPrototype($componentDef->getInstanceMode())) {
            return new PrototypeComponentDeployer($componentDef);
        } else if (InstanceModeUtil::isRequest($componentDef->getInstanceMode())) {
            return new RequestComponentDeployer($componentDef);
        } else if (InstanceModeUtil::isSession($componentDef->getInstanceMode())) {
            return new SessionComponentDeployer($componentDef);
        } else {
            return new OuterComponentDeployer($componentDef);
        }
    }
}
?>