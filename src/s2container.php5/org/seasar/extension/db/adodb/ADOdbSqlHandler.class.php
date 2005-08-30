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
// $Id: ADOdbSqlHandler.class.php,v 1.1 2005/07/02 12:34:41 klove Exp $
/**
 * @package org.seasar.extension.db.adodb
 * @author klove
 */
class ADOdbSqlHandler implements SqlHandler {
	private $log_;

	public function ADOdbSqlHandler(){
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          DataSource $dataSource,
	                          ResultSetHandler $resultSetHandler) {
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