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
// $Id: ArrayResultSetHandler.class.php,v 1.1 2005/06/21 16:33:45 klove Exp $
/**
 * @package org.seasar.extension.db.impl
 * @author klove
 */
class ArrayResultSetHandler implements ResultSetHandler {

	public function ArrayResultSetHandler() {}

	public function handle($row){
		$ret = array();
		foreach($row as $key=>$val){
			array_push($ret,$val);
		}
		return $ret;
	}
}
?>