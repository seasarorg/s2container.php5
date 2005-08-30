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
// $Id: SessionComponentDeployer.class.php,v 1.1 2005/05/28 16:50:12 klove Exp $
/**
 * @package org.seasar.framework.container.deployer
 * @author klove
 */
class SessionComponentDeployer extends AbstractComponentDeployer {

    private static $logger_;

    /**
     * @param ComponentDef
     */
    public function SessionComponentDeployer(ComponentDef $componentDef) {
        parent::__construct($componentDef);
        $this->logger_ = S2Logger::getLogger(get_class($this));        
    }

    public function deploy() {
        $cd = $this->getComponentDef();
        $className = $cd->getComponentClass()->getName();

        $componentName = $cd->getComponentName();
        if ($componentName == null) {
            $componentName = $className;
        }

        $component = null;
        if(isset($_SESSION[$componentName])){
             $component = $_SESSION[$componentName];
        }

        if ($component != null){
            if($component instanceof $className) {
                return $component;
            }else{
                $this->logger_->warn(
                    MessageUtil::getMessageWithArgs(
                        'ESSR1005',
                        array('Session',$componentName,$className)),
                    __METHOD__);
            }
        }
        $component = $this->getConstructorAssembler()->assemble();
        $_SESSION[$componentName] = $component;
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