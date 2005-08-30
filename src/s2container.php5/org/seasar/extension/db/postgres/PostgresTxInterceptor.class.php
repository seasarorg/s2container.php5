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
 * @package org.seasar.extension.db.postgres
 * @author klove
 */
class PostgresTxInterceptor extends AbstractTxInterceptor {
	private $log_;
	
	public function PostgresTxInterceptor(DBSession $session) {
		parent::__construct($session);
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

    function begin(){
    	try{
    	    $this->session->connect();
    	}catch(Exception $e){
    		throw $e;
    	}

    	$ret = pg_query($this->session->getConnection(),'START TRANSACTION');
    	if(!$ret){
    		$this->log_->error(pg_result_error($this->session->getConnection()),
    		                   __METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	$this->log_->info("start transaction.",__METHOD__);
    }

    function commit(){
    	$ret = pg_query($this->session->getConnection(),'COMMIT');
    	if(!$ret){
    		$this->log_->error(pg_result_error($this->session->getConnection()),
    		                   __METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	
    	$this->session->disconnect();
    	$this->log_->info("transaction commit and disconnect.",__METHOD__);
    }

    function rollback(){
    	$ret = pg_query($this->session->getConnection(),'ROLLBACK');
    	if(!$ret){
    		$this->log_->error(pg_result_error($this->session->getConnection()),
    		                   __METHOD__);
    	}
    	$this->session->disconnect();
    	$this->log_->info("transaction rollback and disconnect.",__METHOD__);
    }

    function hasTransaction(){
    	return $this->session->hasConnected();
    }
}
?>