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
 * @package org.seasar.framework.container
 * @author klove
 */
interface S2Container extends S2Container_MetaDefAware
{
    /**
     * @param string
     * @return object
     * @throws S2Container_ComponentNotFoundRuntimeException
     * @throws S2Container_TooManyRegistrationRuntimeException
     * @throws S2Container_CyclicReferenceRuntimeException
     */
    public function getComponent($componentKey);

    /**
     * @param string componentKey
     * @throws S2Container_CyclicReferenceRuntimeException
     */
    public function findComponents($componentKey);

    /**
     * @param object
     * @param string
     * @throws S2Container_ClassUnmatchRuntimeException
     */
    public function injectDependency($outerComponent,$componentName = "");
    
    /**
     * @param object
     * @param string
     */
    public function register($component, $componentName = "");

    /**
     * @return int
     */
    public function getComponentDefSize();

    /**
     * @param int
     * @return S2Container_ComponentDef
     * @throws S2Container_ComponentNotFoundRuntimeException
     */
    public function getComponentDef($index);

    /**
     * @param string componentKey
     */
    public function findComponentDefs($componentKey);

    /**
     * @param string
     * @return boolean
     */
    public function hasComponentDef($componentKey);
    
    /**
     * @param string
     * @return boolean
     */
    public function hasDescendant($path);

    /**
     * @param string
     * @return S2Container
     * @throws S2Container_ContainerNotRegisteredRuntimeException
     */    
    public function getDescendant($path);
    
    /**
     * @param S2Container
     */
    public function registerDescendant(S2Container $descendant);

    /**
     * @param S2Container
     */
    public function includeChild(S2Container $child);
    
    /**
     * @return int
     */
    public function getChildSize();
    
    /**
     * @param int
     * @return S2Container
     */
    public function getChild($index);

    /**
     * 
     */
    public function init();

    /**
     * 
     */
    public function destroy();

    /**
     * Component reconstruct
     * 
     * @param integer $mode
     *        S2Container_ComponentDef::RECONSTRUCT_NORMAL
     *        S2Container_ComponentDef::RECONSTRUCT_FORCE 
     */
    public function reconstruct($mode = 
                                S2Container_ComponentDef::RECONSTRUCT_NORMAL);

    /**
     * @return string
     */    
    public function getNamespace();

    /**
     * @param string
     */    
    public function setNamespace($namespace);

    /**
     * @return string
     */    
    public function getPath();

    /**
     * @param string
     */    
    public function setPath($path);

    /**
     * @return S2Container
     */
    public function getRoot();

    /**
     * @param S2Container
     */    
    public function setRoot(S2Container $root);
}
?>
