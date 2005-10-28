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
 * @package org.seasar.extension.db.adodb
 * @author klove
 */
class S2Container_ADOdbSqlHandler implements S2Container_SqlHandler {
	private $log_;

	public function S2Container_ADOdbSqlHandler(){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          S2Container_DataSource $dataSource,
	                          S2Container_ResultSetHandler $resultSetHandler) {
        try{
            $db = $dataSource->getConnection();
        }catch(Exception $e){	
            throw $e;
        }
        $db->SetFetchMode(ADODB_FETCH_ASSOC);
        
        try{
            if($dataSource->isCache()){
                $result = $db->CacheExecute($dataSource->getCacheSecs(),$sql);
                $this->log_->info("find data from cache. life time : ".$dataSource->getCacheSecs() ."s.",
                                   __METHOD__);
            }else{
                $result = $db->Execute($sql);
            }
        }catch (Exception $e){	
        	$db->disconnect();
            throw $e;
        }

        if(preg_match("/^insert/",trim(strtolower($sql))) or
           preg_match("/^update/",trim(strtolower($sql))) or
           preg_match("/^delete/",trim(strtolower($sql)))){
        	return $db->Affected_Rows();
        }

        $ret = array();
        while($row = $result->fetchRow()){
            array_push($ret,$resultSetHandler->handle($row));
        }
        $result->Close();
        $db->disconnect();
        return $ret;
 	}
}
?>