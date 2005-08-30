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
class DaoMetaDataFactoryImpl implements DaoMetaDataFactory {

	private $daoMetaDataCache_ = array();

	private $dataSource_;

	private $sqlHandler_;
	
	private $statementFactory_;

	private $resultSetFactory_;

	public function DaoMetaDataFactoryImpl(DataSource $dataSource,
	                                         SqlHandler $handler){
		$this->dataSource_ = $dataSource;
		$this->sqlHandler_ = $handler;
	}

	public function getDaoMetaData($daoClass) {
		$key = $daoClass->getName();
		if(array_key_exists($key,$this->daoMetaDataCache_)){
			return $this->daoMetaDataCache_[$key];
		}
		$dmd = new DaoMetaDataImpl($daoClass,
		                            $this->dataSource_,
		                            $this->sqlHandler_);
		$this->daoMetaDataCache_[$key] = $dmd;

		return $dmd;
	}
}
?>