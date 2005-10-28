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
 * @package org.seasar.extension.db.impl
 * @author klove
 */
abstract class S2Container_AbstractDataSource implements S2Container_DataSource {

    protected $user ="";
    protected $password ="";
    protected $host ="";
    protected $port ="";
    protected $database ="";
        
    public function S2Container_AbstractDataSource(){}
    
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