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
// $Id: AbstractTxInterceptor.class.php,v 1.2 2005/06/28 13:48:37 klove Exp $
/**
 * @package org.seasar.extension.db.interceptors
 * @author klove
 */
abstract class AbstractTxInterceptor implements MethodInterceptor {
	protected $session;
	
	public function AbstractTxInterceptor(DBSession $session) {
		$this->session = $session;
	}

    abstract function begin();
    abstract function commit();
    abstract function rollback();
    abstract function hasTransaction();
    
	public function invoke(MethodInvocation $invocation){

		$began = false;
		if (!$this->hasTransaction()) {
			$this->begin();
			$began = true;
		}else{
		    $this->rollback();
		    throw new S2RuntimeException('ESSR0017',array('cannot start nested transction.'));
		}
		 
		$ret = null;

		try {
			$ret = $invocation->proceed();
			if ($began) {
				$this->commit();
			}
			return $ret;
		} catch (Exception $e) {
			if ($began) {
				$this->rollback();
			}
			throw $e;
		}
	}
}
?>