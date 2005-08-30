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
 *
 * @package org.seasar.framework.beans
 * @author klove
 */
class IllegalPropertyRuntimeException extends S2RuntimeException {

    private $componentClass_;
	private $propertyName_;

	public function IllegalPropertyRuntimeException(
		$componentClass,
		$propertyName,
		$cause = null) {

	    $cause != null ? $causeMsg = $cause->getMessage() : 
	                      $causeMsg = "";
		parent::__construct(
			"ESSR0059",
			array($componentClass->getName(), $propertyName, $causeMsg),$cause);
		$this->componentClass_ = $componentClass;
		$this->propertyName_ = $propertyName;
	}

	public function getComponentClass() {
		return $this->componentClass_;
	}
	
	public function getPropertyName() {
		return $this->propertyName_;
	}
}
?>