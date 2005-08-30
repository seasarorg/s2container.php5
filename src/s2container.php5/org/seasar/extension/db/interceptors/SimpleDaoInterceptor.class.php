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
// $Id: SimpleDaoInterceptor.class.php,v 1.1 2005/06/21 16:33:47 klove Exp $
/**
 * @package org.seasar.extension.db.interceptors
 * @author klove
 */
class SimpleDaoInterceptor extends AbstractInterceptor {

	private $daoMetaDataFactory_;

	public function SimpleDaoInterceptor(DaoMetaDataFactory $daoMetaDataFactory) {
		$this->daoMetaDataFactory_ = $daoMetaDataFactory;
	}

	public function invoke(MethodInvocation $invocation) {
		$method = $invocation->getMethod();
		if (!MethodUtil::isAbstract($method)) {
			return $invocation->proceed();
		}

		$targetClass = $this->getTargetClass($invocation);
		$daoInterface = DaoMetaDataImpl::getDaoInterface($targetClass);
		
		$dmd = $this->daoMetaDataFactory_->getDaoMetaData($daoInterface);

		$cmd = $dmd->getSqlCommand($method->getName());
		return $cmd->execute($invocation->getArguments());
	}
}
?>