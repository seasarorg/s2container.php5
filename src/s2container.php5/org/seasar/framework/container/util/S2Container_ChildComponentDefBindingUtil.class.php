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
 * @package org.seasar.framework.container.util
 * @author klove
 */
final class S2Container_ChildComponentDefBindingUtil {
    private static $unresolved = array();

    /**
     * 
     */
    private function __construct()
    {
    }

    public static function init()
    {
        self::$unresolved = array();
    }
    
    public static function put($componentName, 
                               S2Container_ArgDef $argDef)
    {
        if(isset(self::$unresolved[$componentName])){
            array_push(self::$unresolved[$componentName],$argDef);
        }else{
            self::$unresolved[$componentName] = array($argDef);
        }
    }

    public static function bind(S2Container $container)
    {
        foreach (self::$unresolved as $componentName => $argDefs) {
            foreach ($argDefs as $argDef) {
                if ($container->hasComponentDef($componentName)) {
                    $argDef->setChildComponentDef($container->
                                               getComponentDef($componentName));
                    $argDef->setExpression("");
                }
            }
        }
        self::init();
    }
}
?>
