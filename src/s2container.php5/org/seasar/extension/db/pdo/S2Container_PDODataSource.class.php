<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
// |          nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.db.pdo
 * @author nowel
 */
class S2Container_PDODataSource 
    extends S2Container_AbstractDataSource 
{
    private $log_;
    protected $dsn = "";
    protected $option = array();
    protected $db = null;

    /**
     * 
     */
    public function __construct()
    {
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }

    /**
     * 
     */
    public function setDsn($dsn)
    {
        $this->dsn = $dsn; 
    }

    /**
     * 
     */
    public function setOption($option)
    {
        $this->option = $option;
    }
    
    /**
     * 
     */
    public function getConnection()
    {
        if($this->db === null){
            try {
                $this->db = new PDO($this->dsn, $this->user, $this->password, $this->option);
            } catch (PDOException $e) {
                $this->log_->error($e->getMessage(), __METHOD__);
                $this->log_->error($e->getCode(), __METHOD__);
                throw $e;
            }
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->db;
    }

    /**
     * 
     */
    public function disconnect($connection)
    {
        unset($connection);
    }

    /**
     * 
     */
    public function __toString()
    {
        $str = 'user = ' . $this->user . ', ';
        $str .= 'password = ' . $this->password . ', ';
        $str .= 'dsn = ' . $this->dsn . ', ';
        $str .= 'option = ' . implode(',',$this->option);
        return $str;
    }
}
?>
