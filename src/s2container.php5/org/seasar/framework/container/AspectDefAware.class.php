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
 * AspectDefの設定が可能になります。
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface AspectDefAware {
    
    public function addAspectDef(AspectDef $aspectDef);
    
    public function getAspectDefSize();
    
    public function getAspectDef($index);
}
?>
