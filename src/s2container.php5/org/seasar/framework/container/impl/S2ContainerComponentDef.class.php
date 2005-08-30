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
 * @package org.seasar.framework.container.impl
 * @author klove
 */
class S2ContainerComponentDef extends SimpleComponentDef {

    public function S2ContainerComponentDef(S2Container $container, $name) {
        parent::__construct($container, $name);
    }

    public function getContainer() {
        return parent::getComponent();
    }
    
    /**
     * @see ComponentDef::getComponent()
     */
    public function getComponent() {
        return $this->getContainer();
    }

    /**
     * @see ComponentDef::init()
     */
    public function init() {
        $this->getContainer()->init();
    }
    
    /**
     * @see ComponentDef::destroy()
     */
    public function destroy() {
        $this->getContainer()->destroy();
    }
}
?>