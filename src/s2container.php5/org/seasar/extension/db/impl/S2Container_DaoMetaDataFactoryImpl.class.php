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
class S2Container_DaoMetaDataFactoryImpl implements S2Container_DaoMetaDataFactory {

	private $daoMetaDataCache_ = array();

	private $dataSource_;

	private $sqlHandler_;
	
	private $statementFactory_;

	private $resultSetFactory_;

	public function S2Container_DaoMetaDataFactoryImpl(S2Container_DataSource $dataSource,
	                                         S2Container_SqlHandler $handler){
		$this->dataSource_ = $dataSource;
		$this->sqlHandler_ = $handler;
	}

	public function getDaoMetaData($daoClass) {
		$key = $daoClass->getName();
		if(array_key_exists($key,$this->daoMetaDataCache_)){
			return $this->daoMetaDataCache_[$key];
		}
		$dmd = new S2Container_DaoMetaDataImpl($daoClass,
		                            $this->dataSource_,
		                            $this->sqlHandler_);
		$this->daoMetaDataCache_[$key] = $dmd;

		return $dmd;
	}
}
?>