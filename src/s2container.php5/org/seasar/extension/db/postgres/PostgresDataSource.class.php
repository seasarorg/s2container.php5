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
class PostgresDataSource extends AbstractDataSource {
	private $log_;
    protected $dsn ="";
        
    public function PostgresDataSource(){
		$this->log_ = S2Logger::getLogger(get_class($this));
    }
    
    public function setDsn($dsn){
        $this->dsn = $dsn;	
    }

    public function getConnection(){
    	if($this->dsn == ""){
            $this->dsn =$this->constructDsn();
    	}

    	$db = pg_connect($this->dsn);

    	if(!$db){
    		$this->log_->error(pg_result_error($db),
    		                   __METHOD__);
        	$str = 'user = ' . $this->user . ', ';
    	    $str .= 'host = ' . $this->host . ', ';
    	    $str .= 'port = ' . $this->port . ', ';
    	    $str .= 'database = ' . $this->database;
    		$this->log_->error('connect false. [ ' . $str . ' ]',
    		                   __METHOD__);
    		throw new Exception();
    	}

    	return $db;
    }

    public function disconnect($connection){
    	$ret = pg_close($connection);
    	if(!$ret){
    		$this->log_->error(pg_result_error($db),
    		                   __METHOD__);
    		throw new Exception();
    	}
    }

    public function __toString(){
    	$str = 'user = ' . $this->user . ', ';
    	$str .= 'password = ' . $this->password . ', ';
    	$str .= 'host = ' . $this->host . ', ';
    	$str .= 'port = ' . $this->port . ', ';
    	$str .= 'database = ' . $this->database . ', ';
    	$str .= 'dsn = ' . $this->dsn;
    	return $str;
    }
    
    protected function constructDsn(){
        $dsn = "";

        if($this->host != ""){
        	$dsn .= "host=" . $this->host . " ";
        }

        if($this->port != ""){
        	$dsn .= "port=" . $this->port . " ";
        }

        if($this->user != ""){
            $dsn .= "user=" . $this->user . " ";
        }
        
        if($this->password != ""){
            $dsn .= "password=" . $this->password . " ";
        }

        if($this->database != ""){
            $dsn .= "dbname=" . $this->database . " ";
        }

        return $dsn;
    }
}
?>