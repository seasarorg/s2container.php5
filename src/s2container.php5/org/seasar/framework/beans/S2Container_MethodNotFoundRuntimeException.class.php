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
 * @package org.seasar.framework.beans
 * @author klove
 */
final class S2Container_MethodNotFoundRuntimeException 
    extends S2Container_S2RuntimeException
{
    private $targetClass_;
    private $methodName_;
    private $methodArgClasses_;

    /**
     * 
     */
    public function __construct($targetClass,
        $methodName,
        $methodArgs)
    {
        parent::__construct("ESSR0049",
            array($targetClass->getName(),$methodName));
        $this->targetClass_ = $targetClass;
        $this->methodName_ = $methodName;
    }

    /**
     * 
     */
    public function getTargetClass()
    {
        return $this->targetClass_;
    }

    /**
     * 
     */
    public function getMethodName()
    {
        return $this->methodName_;
    }

    /**
     * 
     */
    public function getMethodArgClasses()
    {
        return $this->methodArgClasses_;
    }
}
?>
