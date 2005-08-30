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
// $Id: PostgresSqlHandler.class.php,v 1.1 2005/06/28 13:48:37 klove Exp $
/**
 * @package org.seasar.extension.db.postgres
 * @author klove
 */
class PostgresSqlHandler implements SqlHandler {
	private $log_;

	public function PostgresSqlHandler(){
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          DataSource $dataSource,
	                          ResultSetHandler $resultSetHandler) {
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