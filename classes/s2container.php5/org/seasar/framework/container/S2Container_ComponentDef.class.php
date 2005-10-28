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
 * コンポーネントを定義します。
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface S2Container_ComponentDef
    extends
        S2Container_ArgDefAware,
        S2Container_PropertyDefAware,
        S2Container_InitMethodDefAware,
        S2Container_DestroyMethodDefAware,
        S2Container_AspectDefAware,
        S2Container_MetaDefAware {

    public function getComponent();
        
    public function injectDependency($outerComponent);

    public function getContainer();

    public function setContainer(S2Container $container);

    public function getComponentClass();

    public function getComponentName();

    public function getConcreteClass();

    public function getAutoBindingMode();

    public function setAutoBindingMode($mode);

    public function getInstanceMode();

    public function setInstanceMode($mode);
    
    public function getExpression();

    public function setExpression($expression);

    public function init();

    public function destroy();
}
?>
