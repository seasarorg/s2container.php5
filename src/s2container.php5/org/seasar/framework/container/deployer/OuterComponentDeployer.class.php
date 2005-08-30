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
// $Id: OuterComponentDeployer.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
class OuterComponentDeployer extends AbstractComponentDeployer {

    /**
     * @param ComponentDef
     */
    public function OuterComponentDeployer(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    /**
     * @see ComponentDeployer::deploy()
     */
    public function deploy() {
        throw new UnsupportedOperationException("deploy");
    }
    
    public function injectDependency($outerComponent) {
        $this->checkComponentClass($outerComponent);
        $this->getPropertyAssembler()->assemble($outerComponent);
        $this->getInitMethodAssembler()->assemble($outerComponent);
    }
    
    private function checkComponentClass($outerComponent){
        $componentClass = $this->getComponentDef()->getComponentClass();
        if ($componentClass == null) {
            return;
        }

        if (!is_a($outerComponent,$componentClass->getName())) {
            throw new ClassUnmatchRuntimeException(
                $componentClass,
                new ReflectionClass($outerComponent));
        }
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