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
 * @package org.seasar.extension.db.mysql
 * @author klove
 */
class S2Container_MySQLDataSource extends S2Container_AbstractDataSource {
	private $log_;
        
    public function S2Container_MySQLDataSource(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
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