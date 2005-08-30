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
// $Id: DaoMetaData.class.php,v 1.1 2005/06/21 16:33:46 klove Exp $
/**
 * @package org.seasar.extension.db
 * @author klove
 */
interface DaoMetaData {

	const BEAN = "BEAN";
	const QUERY = "QUERY";
	const FILE = "FILE";

	public function getBeanClass();

	public function getBeanMetaData();

	public function hasSqlCommand($methodName);

	public function getSqlCommand($methodName);
}
?>