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
// $Id: ArgDefAware.class.php,v 1.1 2005/05/28 16:50:11 klove Exp $
/**
 * ArgDef‚ÌÝ’è‚ª‰Â”\‚É‚È‚è‚Ü‚·B
 * 
 * @package org.seasar.framework.container
 * @author klove
 */
interface ArgDefAware {
    
    public function addArgDef(ArgDef $argDef);
    
    public function getArgDefSize();
    
    public function getArgDef($index);
}
?>
