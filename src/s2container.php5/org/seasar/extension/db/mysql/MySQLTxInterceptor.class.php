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
// $Id: MySQLTxInterceptor.class.php,v 1.1 2005/06/21 16:33:48 klove Exp $
/**
 * @package org.seasar.extension.db.mysql
 * @author klove
 */
class MySQLTxInterceptor extends AbstractTxInterceptor {
	private $log_;
	
	public function MySQLTxInterceptor(DBSession $session) {
		parent::__construct($session);
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

    function begin(){
    	try{
    	    $this->session->connect();
    	}catch(Exception $e){
    		throw $e;
    	}
    	$ret = mysql_query('SET AUTOCOMMIT=0',$this->session->getConnection());
    	if(!$ret){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	$ret = mysql_query('START TRANSACTION',$this->session->getConnection());
    	if(!$ret){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	$this->log_->info("start transaction.",__METHOD__);
    }

    function commit(){
    	$ret = mysql_query('COMMIT',$this->session->getConnection());
    	if(!$ret){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	
    	$this->session->disconnect();
    	$this->log_->info("transaction commit and disconnect.",__METHOD__);
    }

    function rollback(){
    	$ret = mysql_query('ROLLBACK',$this->session->getConnection());
    	if(!$ret){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
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