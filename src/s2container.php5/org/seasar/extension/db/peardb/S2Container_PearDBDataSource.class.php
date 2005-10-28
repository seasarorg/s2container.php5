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
class S2Container_PearDBDataSource extends S2Container_AbstractDataSource {
	private $log_;
    protected $type ="";
    protected $dsn ="";
        
    public function S2Container_PearDBDataSource(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
    }
    
    public function setType($type){
        $this->type = $type;	
    }

    public function setDsn($dsn){
        $this->dsn = $dsn;	
    }

    public function getConnection(){
    	if($this->dsn == ""){
            $this->dsn = $this->constructDsn();
    	}
    	
    	$db = DB::connect($this->dsn);
    	if(DB::isError($db)){
    		$this->log_->error($db->getMessage(),__METHOD__);
    		$this->log_->error($db->getDebugInfo(),__METHOD__);
    		throw new Exception();
    	}
    	
    	return $db;
    }

    public function disconnect($connection){
    	$ret = $connection->disconnect();
    	if(DB::isError($ret)){
    		$this->log_->error($ret->getMessage(),__METHOD__);
    		$this->log_->error($ret->getDebugInfo(),__METHOD__);
    		throw new Exception();
    	}
    }

    public function __toString(){
    	$str  = 'type = ' . $this->type . ', ';
    	$str .= 'user = ' . $this->user . ', ';
    	$str .= 'password = ' . $this->password . ', ';
    	$str .= 'host = ' . $this->host . ', ';
    	$str .= 'port = ' . $this->port . ', ';
    	$str .= 'database = ' . $this->database . ', ';
    	$str .= 'dsn = ' . $this->dsn;
    	return $str;
    }

    protected function constructDsnArray(){
    	$dsn = array();
        if($this->type != ""){
        	$dsn['phptype'] = $this->type;
        }

        if($this->user != ""){
        	$dsn['username'] = $this->user;
        }
        
        if($this->password != ""){
        	$dsn['password'] = $this->password;
        }

        if($this->host != ""){
        	$hp = $this->host;
            if($this->port != ""){
            	$hp .= ":" . $this->port;
            }
            
            $dsn['hostspec'] = $hp;
        }

        if($this->database != ""){
            $dsn['database'] = $this->database;
        }
        
        return $dsn;
    }

    protected function constructDsn(){
    	$dsn = "";
        if($this->type != ""){
        	$dsn .= $this->type . "://";
        }

        if($this->user != ""){
        	$dsn .= $this->user;
        }
        
        if($this->password != ""){
        	$dsn .= ":" . $this->password;
        }

        if($this->host != ""){
        	$dsn .= "@" . $this->host;
            if($this->port != ""){
            	$dsn .= ":" . $this->port;
            }
        }

        if($this->database != ""){
        	$dsn .= "/" . $this->database;
        }
        
        return $dsn;
    }
}
?>