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
 * @package org.seasar.framework.container.util
 * @author klove
 */
class AopProxyUtil {
    private function AopProxyUtil() {
    }

    public static function getProxyObject(ComponentDef $componentDef,$args) {
        $parameters = array();
        $parameters[ContainerConstants::COMPONENT_DEF_NAME] = $componentDef;
        $proxy = new AopProxy($componentDef->getComponentClass(),
                               AopProxyUtil::getAspects($componentDef),
                               $parameters);
        return $proxy->create("",$args);
    }

    private static function getAspects(ComponentDef $componentDef) {
        $size = $componentDef->getAspectDefSize();
        $aspects = array();
        for ($i = 0; $i < $size; ++$i) {
            array_push($aspects,$componentDef->getAspectDef($i)->getAspect());
        }
        return $aspects;
    }
}
?>