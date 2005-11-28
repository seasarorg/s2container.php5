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
class S2Container_FieldNotFoundRuntimeException
    extends S2Container_S2RuntimeException
{

    private $targetClass_;
    private $fieldName_;

    /**
     * 
     */
    public function __construct($componentClass,
        $fieldName)
    {
        parent::__construct("ESSR0070",
                  array($componentClass->getName(),$fieldName));
            
        $this->targetClass_ = $componentClass;
        $this->fieldName_ = $fieldName;
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
    public function getFieldName()
    {
        return $this->fieldName_;
    }
}
?>
