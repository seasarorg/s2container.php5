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
class AutoPropertyAssembler extends ManualPropertyAssembler {

    private $log_;
    
    public function AutoPropertyAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
        $this->log_ = S2Logger::getLogger(get_class($this));
    }

    /**
     * @see PropertyAssembler::assemble()
     */
    public function assemble($component) {
        parent::assemble($component);
        $beanDesc = $this->getBeanDesc($component);
        $container = $this->getComponentDef()->getContainer();

        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); ++$i) {
            $value = null;
            $propDesc = $beanDesc->getPropertyDesc($i);
            $propName = $propDesc->getPropertyName();
            if (!$this->getComponentDef()->hasPropertyDef($propName) and
                $propDesc->getWriteMethod() != null and
                AutoBindingUtil::isSuitable($propDesc->getPropertyType())) {

                try {
                    $value = $container->getComponent($propDesc->getPropertyType()->getName());

                } catch (ComponentNotFoundRuntimeException $ex) {
                    if ($propDesc->getReadMethod() != null and
                        $propDesc->getValue($component) != null) {
                        continue;
                    }
                    $this->log_->info($ex->getMessage().". skip property<$propName>.",
                                      __METHOD__);
                    continue;
                }

                $this->setValue($propDesc,$component,$value);
            }
        }
    }
}
?>
