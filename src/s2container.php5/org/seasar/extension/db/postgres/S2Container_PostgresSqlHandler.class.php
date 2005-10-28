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
class S2Container_PostgresSqlHandler implements S2Container_SqlHandler {
	private $log_;

	public function S2Container_PostgresSqlHandler(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          S2Container_DataSource $dataSource,
	                          S2Container_ResultSetHandler $resultSetHandler) {
        $db = $dataSource->getConnection();

        $result = pg_query($db,$sql); 
        
        if(!$result){
    		$this->log_->error(pg_result_error($db),
    		                   __METHOD__);
           	$db->disconnect();
        	throw new Exception();
        }

        if(preg_match("/^insert/",trim(strtolower($sql))) or
           preg_match("/^update/",trim(strtolower($sql))) or
           preg_match("/^delete/",trim(strtolower($sql))) ){
        	return pg_affected_rows($result);
        }
        
        $ret = array();
        $limit = pg_numrows($result);
        for($i=0;$i<$limit;$i++){
            $row = pg_fetch_array($result,$i,PGSQL_ASSOC);
            array_push($ret,$resultSetHandler->handle($row));
        }
        pg_free_result($result);
        $dataSource->disconnect($db);
        return $ret;
 	}
}
?>