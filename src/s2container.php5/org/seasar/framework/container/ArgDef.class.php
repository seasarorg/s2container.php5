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
 * �������`���܂��B
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface ArgDef extends MetaDefAware {
    
    public function getValue();
    
    public function getContainer();
    
    public function setContainer($container);
    
    public function getExpression();

    public function setExpression($str);
    
    public function setChildComponentDef(ComponentDef $componentDef);

}
?>
