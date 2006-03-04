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
 * @package org.seasar.extension.autoregister.factory
 * @author klove
 */
interface S2Container_AnnotationHandler {

    public function createComponentDef(ReflectionClass $componentClass,
                                                         $instanceMode);

    //public function createComponentDef(Class componentClass, InstanceDef instanceDef);
  
    public function appendDI(S2Container_ComponentDef $componentDef);
    
    public function appendAspect(S2Container_ComponentDef $componentDef);
    
    public function appendInitMethod(S2Container_ComponentDef $componentDef);
    
    public function createPropertyDef(S2Container_BeanDesc $beanDesc,
                                      S2Container_PropertyDesc $propertyDesc);
}
?>
