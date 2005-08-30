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
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
class PrototypeComponentDeployer extends AbstractComponentDeployer {

    /**
     * @param ComponentDef
     */
    public function PrototypeComponentDeployer(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see ComponentDeployer::deploy()
     */
    public function deploy() {
        $component = $this->getConstructorAssembler()->assemble();
        $this->getPropertyAssembler()->assemble($component);
        $this->getInitMethodAssembler()->assemble($component);
        return $component;
    }
    
    public function injectDependency($component) {
        throw new UnsupportedOperationException("injectDependency");
    }
    
    /**
     * @see ComponentDeployer::init()
     */
    public function init() {
    }
    
    /**
     * @see ComponentDeployer::destroy()
     */
    public function destroy() {
    }
}
?>
