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
class SqlCommandImpl implements SqlCommand {
	private $log_;

	private $dataSource_;
	private $sqlHandler_;
	private $resultSetHandler_;
	private $sql_;
    private $argNames_;

	public function SqlCommandImpl(DataSource $dataSource,
	                                 SqlHandler $sqlHandler,
	                                 ResultSetHandler $resultSetHandler){
		$this->log_ = S2Logger::getLogger(get_class($this));
		$this->dataSource_ = $dataSource;
		$this->sqlHandler_ = $sqlHandler;
		$this->resultSetHandler_ = $resultSetHandler;
	}

	public function getDataSource() {
		return $this->dataSource_;
	}
	
	public function getSql() {
		return $this->sql_;
	}

	public function setSql($sql) {
		$this->sql_ = $sql;
	}

	public function getArgNames() {
		return $this->argNames_;
	}

	public function setArgNames($argNames) {
		$this->argNames_ = $argNames;
	}
	
	public function execute($args) {

		$sql = SqlCommandImpl::constructSql($this->sql_,$this->argNames_,$args);
		if($sql == null || trim($sql) == ""){
		    $this->log_->error("sql empty error. please check sql file. \n",__METHOD__);
		    throw new Exception();	
		}
		
        return $this->sqlHandler_->execute($sql,
                                            $this->getDataSource(),
                                            $this->resultSetHandler_);
	}

    /**
     * @param string sql line
     * @param string arg names
     * @param string arg values
     * @return string sql line
     */  
    public static function constructSql($sql,
                                     $argNames,
                                     $args){

        for($i=0;$i<count($args);$i++){
             $$argNames[$i] = $args[$i];       	
        }

        $exp = trim($sql);
        if(!preg_match("/\sreturn\s/",$exp) and 
           !preg_match("/\nreturn\s/",$exp) and
           !preg_match("/^return\s/",$exp)){
        	if(!preg_match("/^\"/",$exp)){
        		$exp = "\"" . $exp;
        	}
        	if(!preg_match("/\"$/",$exp)){
        		$exp = $exp . "\"";
        	}
            $exp = "return " . $exp;
            if(!preg_match("/;$/",$exp)){
                $exp = $exp . ";";
            }
        }        

        return eval($exp);
    } 
}
?>