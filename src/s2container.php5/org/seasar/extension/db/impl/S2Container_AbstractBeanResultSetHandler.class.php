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
abstract class S2Container_AbstractBeanResultSetHandler implements S2Container_ResultSetHandler {

	private $beanClass_;

	private $beanDesc_;

	public function S2Container_AbstractBeanResultSetHandler($beanClass) {
		$this->setBeanClass($beanClass);
	}

	public function getBeanClass() {
		return $this->beanClass_;
	}

	public function getBeanDesc() {
		return $this->beanDesc_;
	}

	public function setBeanClass($beanClass) {
		$this->beanClass_ = $beanClass;
		$this->beanDesc_ = S2Container_BeanDescFactory::getBeanDesc($beanClass);
	}

    function col2Property($col){
    	
    	if(preg_match("/_/",$col)){
            $items = split("_",$col);    
            $prop = strtolower($items[0]);
            for($i=1;$i<count($items);$i++){
            	$prop .= ucfirst(strtolower($items[$i]));
            }	
            return $prop;	
    	}else{
    	    return strtolower($col);
    	}    	
    }
}
?>