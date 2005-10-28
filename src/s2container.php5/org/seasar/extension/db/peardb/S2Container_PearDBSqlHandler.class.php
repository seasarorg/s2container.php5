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
class S2Container_PearDBSqlHandler implements S2Container_SqlHandler {
	private $log_;

	public function S2Container_PearDBSqlHandler(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          S2Container_DataSource $dataSource,
	                          S2Container_ResultSetHandler $resultSetHandler) {
        $db = $dataSource->getConnection();
        
        $db->setFetchMode(DB_FETCHMODE_ASSOC);
        $result = $db->query($sql); 
        if(DB::isError($result)){
        	$this->log_->error($result->getMessage(),__METHOD__);
        	$this->log_->error($result->getDebugInfo(),__METHOD__);
        	$db->disconnect();
        	exit;
        }
        
        if($result == DB_OK){
        	return $db->affectedRows();
        }
        
        $ret = array();
        while($row = $result->fetchRow()){
            array_push($ret,$resultSetHandler->handle($row));
        }
        $result->free();
        $db->disconnect();
        return $ret;
 	}
}
?>