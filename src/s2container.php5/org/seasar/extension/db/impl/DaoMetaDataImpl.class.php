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
class DaoMetaDataImpl implements DaoMetaData {
	private $log_;

	private $daoClass_;

	private $daoBeanDesc_;

	private $dataSource_;

	private $sqlHandler_;
	
	private $resultSetHandler_;

	private $beanClass_;

	private $beanMetaData_;

	private $sqlCommands_ = array();

	public function DaoMetaDataImpl($daoClass,
	                                  DataSource $dataSource,
	                                  SqlHandler $handler){
		$this->log_ = S2Logger::getLogger(get_class($this));

		$this->daoClass_ = $daoClass;
		$this->daoBeanDesc_ = BeanDescFactory::getBeanDesc($daoClass);

		$beanField = "";
        if($this->daoBeanDesc_->hasConstant(DaoMetaData::BEAN)){
		    $beanField = $this->daoBeanDesc_->getConstant(DaoMetaData::BEAN);
        }
        
        if($beanField != ""){
        	$this->beanClass_ = new ReflectionClass($beanField);
            $this->resultSetHandler_ = new BeanResultSetHandler($this->beanClass_);
        }else{
    	    $this->beanClass_ = null;
            $this->resultSetHandler_ = new ArrayResultSetHandler();
        }
		$this->dataSource_ = $dataSource;
		$this->sqlHandler_ = $handler;
		$this->setupSqlCommand();
	}

	private function setupSqlCommand() {
		$names = $this->daoBeanDesc_->getMethodNames();
		for ($i = 0; $i < count($names); ++$i) {
			$methods = $this->daoBeanDesc_->getMethods($names[$i]);
			if (MethodUtil::isAbstract($methods)) {
				$this->setupMethod($methods);
			}
		}
	}

	private function setupMethod($method) {
		
		$constQuery = $method->getName()."_".DaoMetaData::QUERY;
		$constFile = $method->getName()."_".DaoMetaData::FILE;
		$sql = "";
		if($this->daoBeanDesc_->hasConstant($constQuery)){
		    $sql = $this->daoBeanDesc_->getConstant($constQuery);
        }else if($this->daoBeanDesc_->hasConstant($constFile)){
        	$path = $this->daoBeanDesc_->getConstant($constFile);
        	$path = StringUtil::expandPath($path);
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
		$cmd = new SqlCommandImpl($this->dataSource_,
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
	    throw new MethodNotFoundRuntimeException(
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
		throw new DaoNotFoundRuntimeException($clazz);
	}
}
?>