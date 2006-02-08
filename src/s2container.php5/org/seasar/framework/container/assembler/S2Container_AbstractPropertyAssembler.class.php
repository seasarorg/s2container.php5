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
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
abstract class S2Container_AbstractPropertyAssembler
    extends S2Container_AbstractAssembler
    implements S2Container_PropertyAssembler
{
    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        parent::__construct($componentDef);
    }

    /**
     * @param S2Container_PropertyDef
     * @param object
     */
    protected function getValue(S2Container_PropertyDef $propertyDef, $component)
    {
        try {
            return $propertyDef->getValue();
        } catch (S2Container_ComponentNotFoundRuntimeException $cause) {
            throw new S2Container_IllegalPropertyRuntimeException($this->
                                          getComponentClass($component),
                                          $propertyDef->getPropertyName(),
                                          $cause);
        }
    }

    /**
     * 
     */
    protected function setValue(S2Container_PropertyDesc $propertyDesc,
                                $component,
                                $value)
    {
        if ($value == null) {
            return;
        } 
        try {
            $propertyDesc->setValue($component,$value);
        } catch (Exception $ex) {
            throw new S2Container_IllegalPropertyRuntimeException($this->
                                      getComponentDef()->getComponentClass(),
                                      $propertyDesc->getPropertyName(),
                                      $ex);
        }
    }
}
?>
