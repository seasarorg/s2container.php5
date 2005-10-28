<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.db.interceptors
 * @author klove
 */
abstract class S2Container_AbstractTxInterceptor implements S2Container_MethodInterceptor {
	protected $session;
	
	public function S2Container_AbstractTxInterceptor(S2Container_DBSession $session) {
		$this->session = $session;
	}

    abstract function begin();
    abstract function commit();
    abstract function rollback();
    abstract function hasTransaction();
    
	public function invoke(S2Container_MethodInvocation $invocation){

		$began = false;
		if (!$this->hasTransaction()) {
			$this->begin();
			$began = true;
		}else{
		    $this->rollback();
		    throw new S2Container_S2RuntimeException('ESSR0017',array('cannot start nested transction.'));
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