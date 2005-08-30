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
class DefaultInitMethodAssembler extends AbstractMethodAssembler {

    /**
     * @param ComponentDef
     */
    public function DefaultInitMethodAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see MethodAssembler::assemble()
     */
    public function assemble($component){
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getInitMethodDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $methodDef = $this->getComponentDef()->getInitMethodDef($i);
            $this->invoke($beanDesc,$component,$methodDef);
        }
    }
}
?>