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
class SingletonComponentDeployer extends AbstractComponentDeployer {

    private $component_;
    private $instantiating_ = false;

    /**
     * @param ComponentDef
     */
    public function SingletonComponentDeployer(ComponentDef $componentDef) {
        parent::__construct($componentDef);
    }

    public function deploy() {
        if ($this->component_ == null) {
            $this->assemble();
        }
        return $this->component_;
    }
    
    public function injectDependency($component) {
        throw new UnsupportedOperationException("injectDependency");
    }

    private function assemble() {
        if ($this->instantiating_) {
            throw new CyclicReferenceRuntimeException(
                $this->getComponentDef()->getComponentClass());
        }
        $this->instantiating_ = true;
        $this->component_ = $this->getConstructorAssembler()->assemble();
        $this->instantiating_ = false;
        $this->getPropertyAssembler()->assemble($this->component_);
        $this->getInitMethodAssembler()->assemble($this->component_);
    }
    
    /**
     * @see ComponentDeployer::init()
     */
    public function init() {
        $this->deploy();
    }
  
    /**
     * @see ComponentDeployer::destroy()
     */  
    public function destroy() {
        if ($this->component_ == null) {
            return;
        }
        $this->getDestroyMethodAssembler()->assemble($this->component_);
        $this->component_ = null;
    }
}
?>
