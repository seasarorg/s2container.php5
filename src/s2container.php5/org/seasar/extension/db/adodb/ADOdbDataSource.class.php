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
// $Id$
/**
 * @package org.seasar.extension.db.adodb
 * @author klove
 */
class ADOdbDataSource extends PearDBDataSource {
	private $log_;

    private $cacheDir ="";
    private $cache = false;
    private $cacheSecs;
        
    public function ADOdbDataSource(){
		$this->log_ = S2Logger::getLogger(get_class($this));
    }
    
    public function setCacheDir($cacheDir){
    	$cacheDir = StringUtil::expandPath($cacheDir);
    	global $ADODB_CACHE_DIR;
    	$ADODB_CACHE_DIR = $cacheDir;
        $this->cacheDir = $cacheDir;	
    }

    public function setCacheSecs($cacheSecs){
    	$this->cacheSecs = $cacheSecs;
    }
    
    public function getCacheSecs(){
    	return $this->cacheSecs;
    }
    
    public function setCache($cache){
        $this->cache = $cache;	
    }

    public function isCache(){
        return $this->cache;	
    }

    public function getConnection(){
    	if($this->dsn == ""){
            $this->dsn = $this->constructDsn();
    	}
    	
    	try{
    	    $db = NewADOConnection($this->dsn);
    	}catch(Exception $e){
    		adodb_backtrace($e->gettrace());
    		throw new Exception();
    	}
    	
    	return $db;
    }

    public function disconnect($connection){
    	try{
    	    $connection->close();
    	}catch(Exception $e){
    		adodb_backtrace($e->gettrace());
    		throw new Exception();
    	}
    }

    public function __toString(){
    	$str  = 'type = ' . $this->type . ', ';
    	$str .= 'user = ' . $this->user . ', ';
    	$str .= 'password = ' . $this->password . ', ';
    	$str .= 'host = ' . $this->host . ', ';
    	$str .= 'port = ' . $this->port . ', ';
    	$str .= 'database = ' . $this->database . ', ';
    	$str .= 'dsn = ' . $this->dsn . ', ';
    	$str .= 'cacheDir = ' . $this->cacheDir;
    	return $str;
    }
}
?>