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
// $Id: AbstractConstructorAssembler.class.php,v 1.1 2005/05/28 16:50:11 klove Exp $
/**
 * @package org.seasar.framework.container.assembler
 * @author klove
 */
abstract class AbstractConstructorAssembler extends AbstractAssembler
        implements ConstructorAssembler {

    public function AbstractConstructorAssembler(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    protected function assembleDefault() {
        $clazz = $this->getComponentDef()->getConcreteClass();
        return ConstructorUtil::newInstance($clazz, null,$this->getComponentDef());
    }
}
?>
