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
// $Id: S2SimpleInvoker.class.php,v 1.1 2005/08/02 14:00:02 klove Exp $
/**
 * @package org.seasar.extension.unit.simpletest
 * @author klove
 */
class S2SimpleInvoker extends SimpleInvoker{

    function S2SimpleInvoker($test_case) {
    	parent::__construct($test_case);
    }
    
    /**
     *    Invokes a test method and buffered with setUp()
     *    and tearDown() calls.
     *    @param string $method    Test method to call.
     *    @access public
     */
    function invoke($method) {
        $this->_test_case->runBare($method);
    }    
}
?>