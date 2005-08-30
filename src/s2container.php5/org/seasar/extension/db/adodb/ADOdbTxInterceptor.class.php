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
 * @package org.seasar.extension.db.adodb
 * @author klove
 */
class ADOdbTxInterceptor extends AbstractTxInterceptor {
	private $log_;
	
	public function ADOdbTxInterceptor(DBSession $session) {
		parent::__construct($session);
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

    function begin(){
    	try{
    	    $this->session->connect();
    	}catch(Exception $e){
    		throw $e;
    	}
    	try{
        	$ret = $this->session->getConnection()->BeginTrans();
    	}catch(Exception $e){
        	$this->session->disconnect();
    		throw $e;
    	}
    	$this->log_->info("start transaction.",__METHOD__);
    }

    function commit(){
    	try{
    	    $ret = $this->session->getConnection()->CommitTrans();
    	}catch(Exception $e){
        	$this->session->disconnect();
    		throw $e;
    	}
    	
    	$this->session->disconnect();
    	$this->log_->info("transaction commit and disconnect.",__METHOD__);
    }

    function rollback(){
    	$this->session->getConnection()->RollbackTrans();
    	$this->session->disconnect();
    	$this->log_->info("transaction rollback and disconnect.",__METHOD__);
    }

    function hasTransaction(){
    	return $this->session->hasConnected();
    }
}
?>