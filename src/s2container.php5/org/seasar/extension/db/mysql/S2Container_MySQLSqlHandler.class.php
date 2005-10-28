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
class S2Container_MySQLSqlHandler implements S2Container_SqlHandler {
	private $log_;

	public function S2Container_MySQLSqlHandler(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          S2Container_DataSource $dataSource,
	                          S2Container_ResultSetHandler $resultSetHandler) {
        $db = $dataSource->getConnection();

        $result = mysql_query($sql,$db); 
        if(!$result){
    		$this->log_->error(mysql_errno().": ".mysql_error(),
    		                   __METHOD__);
           	$db->disconnect();
        	throw new Exception();
        }

        if(preg_match("/^insert/",trim(strtolower($sql))) or
           preg_match("/^update/",trim(strtolower($sql))) or
           preg_match("/^delete/",trim(strtolower($sql))) ){
        	return mysql_affected_rows($db);
        }
        
        $ret = array();
        while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
            array_push($ret,$resultSetHandler->handle($row));
        }
        mysql_free_result($result);
        $dataSource->disconnect($db);
        return $ret;
 	}
}
?>