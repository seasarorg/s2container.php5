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
 * @package org.seasar.extension.db.peardb
 * @author klove
 */
class S2Container_PearDBTxInterceptor extends S2Container_AbstractTxInterceptor {
	private $log_;
	
	public function S2Container_PearDBTxInterceptor(S2Container_DBSession $session) {
		parent::__construct($session);
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
	}

    function begin(){
    	try{
    	    $this->session->connect();
    	}catch(Exception $e){
    		throw $e;
    	}
    	$ret = $this->session->getConnection()->autoCommit(false);
    	if(DB::isError($ret)){
    	    $this->log_->error($ret->getMessage(),__METHOD__);
    		$this->log_->error($ret->getDebugInfo(),__METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	$this->log_->info("auto commit false. (start transaction.)",__METHOD__);
    }

    function commit(){
    	$ret = $this->session->getConnection()->commit();
    	if(DB::isError($ret)){
    	    $this->log_->error($ret->getMessage(),__METHOD__);
    		$this->log_->error($ret->getDebugInfo(),__METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	
    	$this->session->disconnect();
    	$this->log_->info("transaction commit and disconnect.",__METHOD__);
    }

    function rollback(){
    	$this->session->getConnection()->rollback();
    	$this->session->disconnect();
    	$this->log_->info("transaction rollback and disconnect.",__METHOD__);
    }

    function hasTransaction(){
    	return $this->session->hasConnected();
    }
}
?>