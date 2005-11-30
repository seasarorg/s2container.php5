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
// |          nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.db.pdo
 * @author nowel
 */
class S2Container_PDOSqlHandler implements S2Container_SqlHandler {

    private $log_;

    public function __construct(){
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }

    public function execute($sql,
                            S2Container_DataSource $dataSource,
                            S2Container_ResultSetHandler $resultSetHandler) {
        
        $db = $dataSource->getConnection();
        
        try {
            $stmt = $db->query($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            $this->log_->error($e->getMessage(), __METHOD__);
            $this->log_->error($e->getCode(), __METHOD__);
            exit();
        }
        
        $count = $stmt->rowCount();
        if($count > 0){
            return $count;
        }
        
        $ret = array();
        while($row = $stmt->fetchRow()){
            array_push($ret, $resultSetHandler->handle($row));
        }
        unset($stmt);
        unset($db);
        return $ret;
     }
}
?>
