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
// $Id: NoSuchMethodRuntimeException.class.php,v 1.1 2005/08/02 14:00:03 klove Exp $
/**
 * package org.seasar.framework.exception
 * @author klove
 */
 class NoSuchMethodRuntimeException extends S2RuntimeException {

	private $targetClass_;
	private $methodName_;

    /**
     * @param ReflectionClass
     * @param string
     * @param Exception
     */
	public function NoSuchMethodRuntimeException(
		$targetClass = null,
		$methodName,
		$cause = null) {

		parent::__construct(
			"ESSR0057",
			array($targetClass != null ? $targetClass->getName() : "null",
			       $methodName),
			$cause);
		$this->targetClass_ = $targetClass;
		$this->methodName_ = $methodName;
	}
	
	public function getTargetClass() {
		return $this->targetClass_;
	}
	
	public function getMethodName() {
		return $this->methodName_;
	}
}
?>