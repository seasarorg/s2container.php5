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
 * @package org.seasar.extension.db
 * @author klove
 */
class DaoNotFoundRuntimeException extends S2RuntimeException {
	
	private $targetClass_;
	
	public function DaoNotFoundRuntimeException($targetClass) {
		parent::__construct("ESSR0001",array($targetClass->getName()));
		$this->targetClass_ = $targetClass;
	}
	
	public function getTargetClass() {
		return $this->targetClass_;
	}
}
?>