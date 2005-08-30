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
 * @package org.seasar.extension.db.impl
 * @author klove
 */
abstract class AbstractDataSource implements DataSource {

    protected $user ="";
    protected $password ="";
    protected $host ="";
    protected $port ="";
    protected $database ="";
        
    public function AbstractDataSource(){}
    
    public function setUser($user){
        $this->user = $user;	
    }

    public function setPassword($password){
        $this->password = $password;	
    }

    public function setHost($host){
        $this->host = $host;	
    }

    public function setPort($port){
        $this->port = $port;	
    }

    public function setDatabase($database){
        $this->database = $database;	
    }
}
?>