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
 * @package org.seasar.extension.db.mysql
 * @author klove
 */
class MySQLDataSource extends AbstractDataSource {
	private $log_;
        
    public function MySQLDataSource(){
		$this->log_ = S2Logger::getLogger(get_class($this));
    }
    
    public function getConnection(){
    	$db = mysql_connect($this->getHostWithPort(),
    	                   $this->user,
    	                   $this->password);
    	if(!$db){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
    		$this->log_->error('[ host = ' . $this->host .
    		                    ' port = ' . $this->port .
    		                    ' user = ' . $this->user .
    		                    ' ]',
    		                   __METHOD__);
    		throw new Exception();
    	}
    	$res = mysql_select_db($this->database,$db);
    	if(!$res){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
    		throw new Exception();
    	}
    	
    	return $db;
    }

    public function disconnect($connection){
    	$ret = mysql_close($connection);
    	if(!$ret){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
    		throw new Exception();
    	}
    }

    public function __toString(){
    	$str = 'user = ' . $this->user . ', ';
    	$str .= 'password = ' . $this->password . ', ';
    	$str .= 'host = ' . $this->host . ', ';
    	$str .= 'port = ' . $this->port . ', ';
    	$str .= 'database = ' . $this->database;
    	return $str;
    }

    private function getHostWithPort(){
        if(is_numeric($this->port)){
        	return $this->host.':'.$this->port;
        }	
      	return $this->host;
    }        
}
?>