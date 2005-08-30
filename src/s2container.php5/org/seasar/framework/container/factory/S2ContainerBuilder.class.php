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
// $Id: S2ContainerBuilder.class.php,v 1.1 2005/05/28 16:50:11 klove Exp $
/**
 * @package org.seasar.framework.container.factory
 * @author klove
 */
interface S2ContainerBuilder {

    public function build($path);
    
    public function includeChild(S2Container $parent, $path);
}
?>
