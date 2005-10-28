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
 * @package org.seasar.extension.db.impl
 * @author klove
 */
class S2Container_DBSessionImpl implements S2Container_DBSession {
	private $log_;

    private $connection = null;
    private $dataSource;
    
    function S2Container_DBSessionImpl(S2Container_DataSource $dataSource) {
    	$this->dataSource = $dataSource;
    	$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
    }
    
    function connect(){
    	if($this->connection == null){
   		    $this->connection = $this->dataSource->getConnection();
    	}else{
   			$this->log_->info('connection has already opened.',__METHOD__);
    	}
    }
    
    function getConnection(){ 	
    	return $this->connection;
    }

    function disconnect(){
    	if($this->connection != null){
   		    $this->dataSource->disconnect($this->connection);
    		$this->connection = null;
    	}
    }

    function hasConnected(){
    	return $this->connection != null;
    }
}
?>