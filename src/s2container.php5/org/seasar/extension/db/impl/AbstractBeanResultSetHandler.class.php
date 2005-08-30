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
// $Id: AbstractBeanResultSetHandler.class.php,v 1.1 2005/06/21 16:33:45 klove Exp $
/**
 * @package org.seasar.extension.db.impl
 * @author klove
 */
abstract class AbstractBeanResultSetHandler implements ResultSetHandler {

	private $beanClass_;

	private $beanDesc_;

	public function AbstractBeanResultSetHandler($beanClass) {
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
		$this->beanDesc_ = BeanDescFactory::getBeanDesc($beanClass);
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