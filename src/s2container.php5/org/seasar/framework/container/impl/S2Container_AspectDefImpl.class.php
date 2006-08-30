<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
 * @copyright  2005-2006 the Seasar Foundation and the Others.
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    Release: 1.1.2
 * @link       http://s2container.php5.seasar.org/
 * @since      Class available since Release 1.0.0
 * @package    org.seasar.framework.container.impl
 * @author     klove
 */
class S2Container_AspectDefImpl 
    extends S2Container_ArgDefImpl 
    implements S2Container_AspectDef
{
    private $pointcut_;

    /**
     * 
     */
    public function __construct($arg1 = null,$arg2 = null)
    {
        parent::__construct();
        
        if ($arg1 instanceof S2Container_Pointcut) {
            $this->pointcut_ = $arg1;
        }
        if ($arg2 instanceof S2Container_Pointcut) {
            $this->pointcut_ = $arg2;
        }
        if ($arg1 instanceof S2Container_MethodInterceptor) {
            $this->setValue($arg1);
        }
        if ($arg2 instanceof S2Container_MethodInterceptor) {
            $this->setValue($arg2);
        }
    }

    /**
     * @see S2Container_AspectDef::getPointcut()
     */    
    public function getPointcut() {
    	return $this->pointcut_;
    }
    
    /**
     * @see S2Container_AspectDef::setPointcut()
     */    
    public function setPointcut(S2Container_Pointcut $pointcut) {
        $this->pointcut_ = $pointcut;
    }

    /**
     * @see S2Container_AspectDef::getAspect()
     */    
    public function getAspect()
    {
        $interceptor = $this->getValue();
        return new S2Container_AspectImpl($interceptor, $this->pointcut_);
    }
}
?>
