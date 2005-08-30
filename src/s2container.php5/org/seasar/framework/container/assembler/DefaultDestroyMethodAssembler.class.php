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
// $Id: DefaultDestroyMethodAssembler.class.php,v 1.1 2005/05/28 16:50:11 klove Exp $
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
class DefaultDestroyMethodAssembler extends AbstractMethodAssembler {

    /**
     * @param ComponentDef
     */
    public function DefaultDestroyMethodAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see MethodAssembler::assemble()
     */
    public function assemble($component){
        $beanDesc = $this->getBeanDesc($component);
        $size = $this->getComponentDef()->getDestroyMethodDefSize();
        for ($i = 0; $i < $size; ++$i) {
            $methodDef = $this->getComponentDef()->getDestroyMethodDef($i);
            $this->invoke($beanDesc,$component,$methodDef);
        }
    }

}
?>
