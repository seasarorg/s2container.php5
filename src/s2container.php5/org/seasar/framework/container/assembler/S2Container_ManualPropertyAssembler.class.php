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
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class S2Container_ManualPropertyAssembler
    extends S2Container_AbstractPropertyAssembler
{
    /**
     * @param S2Container_ComponentDef
     */
    public function __construct(S2Container_ComponentDef $componentDef)
    {
        parent::__construct($componentDef);
    }

    /**
     * @see S2Container_PropertyAssembler::assemble()
     */
    public function assemble($component)
    {
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getPropertyDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $propDef = $this->getComponentDef()->getPropertyDef($i);
            $value = null;
            try {
                $propDesc =
                    $beanDesc->getPropertyDesc($propDef->getPropertyName());
                if (!$propDesc->hasWriteMethod()) {
                    throw new S2Container_PropertyNotFoundRuntimeException($beanDesc->getBeanClass(),
                              $propDef->getPropertyName());
                }
                try {
                    $value = $this->getValue($propDef, $component);
                } catch(S2Container_TooManyRegistrationRuntimeException $tooManyException) {
                    $refParams = $propDesc->getWriteMethod()->getParameters();
                    $typeHintRefClass = $refParams[0]->getClass();
                    if ($refParams[0]->isArray()) {
                        $componentDefs = $propDef->getChildComponentDef()->getComponentDefs();
                        $value = array();
                        foreach ($componentDefs as $componentDef) {
                            $value[] = $componentDef->getComponent();
                        }
                    } else if ($typeHintRefClass instanceof ReflectionClass and
                               $typeHintRefClass->getName() == 'ArrayObject') {
                        $componentDefs = $propDef->getChildComponentDef()->getComponentDefs();
                        $value = new ArrayObject();
                        foreach ($componentDefs as $componentDef) {
                            $value[] = $componentDef->getComponent();
                        }
                    } else {
                        throw $tooManyException;
                    }
                }
            } catch (S2Container_PropertyNotFoundRuntimeException $e1) {
                try {
                    $propDesc =
                        $beanDesc->getPropertyDesc('__set');
                    $propDesc->setSetterPropertyName($propDef->getPropertyName());
                    $value = $this->getValue($propDef, $component);
                } catch(S2Container_PropertyNotFoundRuntimeException $e2) {
                    throw $e1;
                }
            }

            $this->setValue($propDesc,$component,$value);
        }
    }
}
?>
