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
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class ManualPropertyAssembler extends AbstractPropertyAssembler {

    /**
     * @param ComponentDef
     */
    public function ManualPropertyAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see PropertyAssembler::assemble()
     */
    public function assemble($component) {
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getPropertyDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $propDef = $this->getComponentDef()->getPropertyDef($i);
            $value = $this->getValue($propDef, $component);
            try{
                $propDesc =
                    $beanDesc->getPropertyDesc($propDef->getPropertyName());
            }catch(PropertyNotFoundRuntimeException $e1){
                try{
                	$propDesc =
                        $beanDesc->getPropertyDesc('__set');
                    $propDesc->setSetterPropertyName($propDef->getPropertyName());    
                }catch(PropertyNotFoundRuntimeException $e2){
                    throw $e1;
                }
            }

            if(!$propDesc->hasWriteMethod()){
            	$propDesc =
                    $beanDesc->getPropertyDesc('__set');
                $propDesc->setSetterPropertyName($propDef->getPropertyName());    
            }    
            $this->setValue($propDesc,$component,$value);
        }
    }
}
?>