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
// $Id: DBSessionImpl.class.php,v 1.2 2005/08/02 14:00:01 klove Exp $
/**
 * @package org.seasar.extension.db.impl
 * @author klove
 */
class DBSessionImpl implements DBSession {
	private $log_;

    private $connection = null;
    private $dataSource;
    
    function DBSessionImpl(DataSource $dataSource) {
    	$this->dataSource = $dataSource;
    	$this->log_ = S2Logger::getLogger(get_class($this));
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