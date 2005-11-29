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
 * @package org.seasar.framework.container
 * @author klove
 */
interface S2Container_ComponentDef
    extends S2Container_ArgDefAware,
        S2Container_PropertyDefAware,
        S2Container_InitMethodDefAware,
        S2Container_DestroyMethodDefAware,
        S2Container_AspectDefAware,
        S2Container_MetaDefAware
{

    /**
     * @return object
     */
    public function getComponent();
        
    /**
     * @param object
     */
    public function injectDependency($outerComponent);

    /**
     * @return S2Container
     */
    public function getContainer();

    /**
     * @param S2Container
     */
    public function setContainer(S2Container $container);

    /**
     * @return ReflectionClass
     */
    public function getComponentClass();

    /**
     * @return string
     */
    public function getComponentName();

    /**
     * @return ReflectionClass
     */
    public function getConcreteClass();

    /**
     * @return int
     */
    public function getAutoBindingMode();

    /**
     * @param int
     */
    public function setAutoBindingMode($mode);

    /**
     * @return int
     */
    public function getInstanceMode();

    /**
     * @param int
     */
    public function setInstanceMode($mode);
    
    /**
     * @return string
     */
    public function getExpression();

    /**
     * @param string
     */
    public function setExpression($expression);

    /**
     * 
     */
    public function init();

    /**
     * 
     */
    public function destroy();
    
    /**
     * 
     */
    public function reconstruct($mode = 
                           S2Container_ComponentDef::RECONSTRUCT_NORMAL);
    const RECONSTRUCT_NORMAL = 0;
    const RECONSTRUCT_FORCE = 1;
}
?>
