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
// | Authors: klove, nowel                                                |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.util
 * @author klove
 * @author nowel
 */
class S2Container_AopProxyUtil
{
    /**
     * 
     */
    private function __construct()
    {
    }

    /**
     * 
     */
    public static function getProxyObject(S2Container_ComponentDef $componentDef,
                                                                   $args)
    {
        if($componentDef->getAspectDefSize() == 0
                && $componentDef->getInterTypeDefSize() == 0) {
            // ComponentClas <= ReflectionClassなのでTestが通らないかも。::assertTypeされてるかも。
            // TODO:: testの不備を直す。かそれ以外(ReflectionClass)の対応をする||それともこれでいく
            //return $componentDef->getComponentClass();
            return S2Container_ConstructorUtil::newInstance($componentDef->getComponentClass(),$args);
        }
        
        $parameters = array();
        $parameters[S2Container_ContainerConstants::COMPONENT_DEF_NAME] = 
                                                                  $componentDef;

        $target = null;
        $componentClass = $componentDef->getComponentClass();
        if (!$componentClass->isInterface() && !$componentClass->isAbstract()) {
            $target = S2Container_ConstructorUtil::newInstance($componentClass, $args);
        }

        return S2Container_AopProxyFactory::create($target,
                   $componentClass,
                   self::getAspects($componentDef),
                   self::getInterTypes($componentDef),
                   $parameters);
    }

    /**
     * 
     */
    private static function getAspects(S2Container_ComponentDef $componentDef)
    {
        $size = $componentDef->getAspectDefSize();
        $aspects = array();
        for ($i = 0; $i < $size; ++$i) {
            $aspects[] = $componentDef->getAspectDef($i)->getAspect();
        }
        return $aspects;
    }
    
    /**
     * 
     */
    protected static function getInterTypes(S2Container_ComponentDef $componentDef) {
        $size = $componentDef->getInterTypeDefSize();
        $interTypes = array();
        for ($i = 0; $i < $size; ++$i) {
            $interTypes[] = $componentDef->getInterTypeDef($i)->getInterType();
        }
        return $interTypes;
    }
}
?>
