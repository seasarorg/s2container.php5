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
// $Id: MySQLSqlHandler.class.php,v 1.2 2005/06/28 13:48:37 klove Exp $
/**
 * @package org.seasar.extension.db.mysql
 * @author klove
 */
class MySQLSqlHandler implements SqlHandler {
	private $log_;

	public function MySQLSqlHandler(){
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          DataSource $dataSource,
	                          ResultSetHandler $resultSetHandler) {
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