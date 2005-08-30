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
// $Id: ComponentDef.class.php,v 1.1 2005/05/28 16:50:11 klove Exp $
/**
 * コンポーネントを定義します。
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface ComponentDef
    extends
        ArgDefAware,
        PropertyDefAware,
        InitMethodDefAware,
        DestroyMethodDefAware,
        AspectDefAware,
        MetaDefAware {

    public function getComponent();
        
    public function injectDependency($outerComponent);

    public function getContainer();

    public function setContainer(S2Container $container);

    public function getComponentClass();

    public function getComponentName();

    public function getConcreteClass();

    public function getAutoBindingMode();

    public function setAutoBindingMode($mode);

    public function getInstanceMode();

    public function setInstanceMode($mode);
    
    public function getExpression();

    public function setExpression($expression);

    public function init();

    public function destroy();
}
?>
