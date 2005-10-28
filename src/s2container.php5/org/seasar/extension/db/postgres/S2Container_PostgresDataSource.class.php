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
 * @package org.seasar.extension.db.postgres
 * @author klove
 */
class S2Container_PostgresDataSource extends S2Container_AbstractDataSource {
	private $log_;
    protected $dsn ="";
        
    public function S2Container_PostgresDataSource(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
    }
    
    public function setDsn($dsn){
        $this->dsn = $dsn;	
    }

    public function getConnection(){
    	if($this->dsn == ""){
            $this->dsn =$this->constructDsn();
    	}

    	$db = pg_connect(trim($this->dsn));
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