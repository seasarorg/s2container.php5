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
class S2Container_DaoMetaDataImpl implements S2Container_DaoMetaData {
	private $log_;

	private $daoClass_;

	private $daoBeanDesc_;

	private $dataSource_;

	private $sqlHandler_;
	
	private $resultSetHandler_;

	private $beanClass_;

	private $beanMetaData_;

	private $sqlCommands_ = array();

	public function S2Container_DaoMetaDataImpl($daoClass,
	                                  S2Container_DataSource $dataSource,
	                                  S2Container_SqlHandler $handler){
		$this->log_ = S2Container_S2Logger::getLogger(get_class($this));

		$this->daoClass_ = $daoClass;
		$this->daoBeanDesc_ = S2Container_BeanDescFactory::getBeanDesc($daoClass);

		$beanField = "";
        if($this->daoBeanDesc_->hasConstant(S2Container_DaoMetaData::BEAN)){
		    $beanField = $this->daoBeanDesc_->getConstant(S2Container_DaoMetaData::BEAN);
        }
        
        if($beanField != ""){
        	$this->beanClass_ = new ReflectionClass($beanField);
            $this->resultSetHandler_ = new S2Container_BeanResultSetHandler($this->beanClass_);
        }else{
    	    $this->beanClass_ = null;
            $this->resultSetHandler_ = new S2Container_ArrayResultSetHandler();
        }
		$this->dataSource_ = $dataSource;
		$this->sqlHandler_ = $handler;
		$this->setupSqlCommand();
	}

	private function setupSqlCommand() {
		$names = $this->daoBeanDesc_->getMethodNames();
		for ($i = 0; $i < count($names); ++$i) {
			$methods = $this->daoBeanDesc_->getMethods($names[$i]);
			if (S2Container_MethodUtil::isAbstract($methods)) {
				$this->setupMethod($methods);
			}
		}
	}

	private function setupMethod($method) {
		
		$constQuery = $method->getName()."_".S2Container_DaoMetaData::QUERY;
		$constFile = $method->getName()."_".S2Container_DaoMetaData::FILE;
		$sql = "";
		if($this->daoBeanDesc_->hasConstant($constQuery)){
		    $sql = $this->daoBeanDesc_->getConstant($constQuery);
        }else if($this->daoBeanDesc_->hasConstant($constFile)){
        	$path = $this->daoBeanDesc_->getConstant($constFile);
        	$path = S2Container_StringUtil::expandPath($path);
    		if(is_readable($path)) {
	    		$sql = file_get_contents($path);
    		} else {
	    		$this->log_->warn("$path is not exist or unreadable.",__METHOD__);
		    }
		}else{
    		$base = dirname($this->daoClass_->getFileName()) . '/';
	    	$base .= $this->daoClass_->getName() . "_" . $method->getName();
		    $standardPath = $base . ".sql";
		    if(is_readable($standardPath)) {
			    $sql = file_get_contents($standardPath);
	    	}else{
		    	$this->log_->info("$standardPath is not exist or unreadable.",__METHOD__);
		    }
		}
		if($sql!=""){
    	    $this->setupMethodByManual($method, $sql);
		}
	}

	private function setupMethodByManual($method,$sql) {
		$cmd = new S2Container_SqlCommandImpl($this->dataSource_,
		                           $this->sqlHandler_,
		                           $this->resultSetHandler_);
		$cmd->setSql($sql);
		$ps = $method->getParameters();
		$args = array();
		for($i=0;$i<count($ps);$i++){
			array_push($args,$ps[$i]->getName());
		}
		$cmd->setArgNames($args);
		$this->sqlCommands_[$method->getName()] = $cmd;
	}
	public function getBeanClass(){
		return $this->daoClass_;
	}

	public function getBeanMetaData() {
		return $this->beanMetaData_;
	}

	public function getSqlCommand($methodName){
		if (array_key_exists($methodName,$this->sqlCommands_)){
    		$cmd = $this->sqlCommands_[$methodName];
			if($cmd != null) {
    		    return $cmd;
			}
		}
	    throw new S2Container_MethodNotFoundRuntimeException(
	               $this->daoClass_,
	               $methodName,
				   null);
	}

	public function hasSqlCommand($methodName) {
		return array_key_exists($methodName,$this->sqlCommands_);
	}

	public static function getDaoInterface($clazz) {
		if ($clazz->isInterface()) {
			return $clazz;
		}
		throw new S2Container_DaoNotFoundRuntimeException($clazz);
	}
}
?>