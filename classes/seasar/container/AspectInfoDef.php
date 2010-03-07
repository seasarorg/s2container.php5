<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2010 the Seasar Foundation and the Others.            |
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
/**
 *
 * @copyright 2005-2010 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.container
 * @author    klove
 */
namespace seasar\container;
class AspectInfoDef {
    private $interceptor = null;
    private $componentPattern = null;
    private $pointcut = null;
    private $condition = null;

    /**
     * @param string $className
     */
    public function __construct($interceptor = null, $componentPattern = null, $pointcut = null) {
        $this->interceptor = $interceptor;
        if (is_null($componentPattern)) {
            $this->componentPattern = '/interceptor/i';
            $this->condition = false;
        } else {
            $this->componentPattern = $componentPattern;
            $this->condition = true;
        }
        $this->pointcut = $pointcut;
    }

    /**
     * @param seasar\container\ComponentDef $cd
     * @return boolean
     */
    public function applicable(\seasar\container\ComponentDef $cd) {
        $result = preg_match($this->componentPattern, $cd->getComponentName()) ||
                  preg_match($this->componentPattern, $cd->getComponentClass()->getName());
        if ($this->condition) {
          return $result;
        } else {
          return !$result;
        }
    }

    /**
     * @S2Aspectアノテーション結果の形で配列を返します。
     * @return array
     */
    public function toAnnotationArray() {
        return array('interceptor' => $this->interceptor, 'pointcut' => $this->pointcut);
    }

    /**
     * @see seasar\container\AspectInfoDef::setInterceptor
     */
    public function interceptor($interceptor) {
        return $this->setInterceptor($interceptor);
    }

    /**
     * @param string $interceptor
     * @return seasar\container\AspectInfoDef
     */
    public function setInterceptor($interceptor) {
        $this->interceptor = $interceptor;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterceptor() {
        return $this->interceptor;
    }

    /**
     * @param string $componentPattern
     * @return seasar\container\AspectInfoDef
     */
    public function setComponentPattern($componentPattern, $condition = true) {
        $this->componentPattern = $componentPattern;
        $this->setCondition($condition);
        return $this;
    }

    /**
     * @return string
     */
    public function getComponentPattern() {
        return $this->componentPattern;
    }

    /**
     * @see seasar\container\AspectInfoDef::setComponentPattern
     */
    public function pattern($componentPattern, $condition = true) {
        return $this->setComponentPattern($componentPattern, $condition);
    }

    /**
     * @see seasar\container\AspectInfoDef::setComponentPattern
     */
    public function setPattern($componentPattern, $condition = true) {
        return $this->setComponentPattern($componentPattern, $condition);
    }

    /**
     * @see seasar\container\AspectInfoDef::getComponentPattern
     */
    public function getPattern() {
        return $this->getComponentPattern();
    }

    /**
     * @see seasar\container\AspectInfoDef::setPointcut
     */
    public function pointcut($pointcut) {
        return $this->setPointcut($pointcut);
    }

    /**
     * @param string $pointcut
     * @return seasar\container\AspectInfoDef
     */
    public function setPointcut($pointcut) {
        $this->pointcut = $pointcut;
        return $this;
    }

    /**
     * @return string
     */
    public function getPointcut() {
        return $this->pointcut;
    }

    /**
     * @see seasar\container\AspectInfoDef::setCondition
     */
    public function condition($condition = true) {
        return $this->setCondition($condition);
    }

    /**
     * @param boolean $condition
     * @return seasar\container\AspectInfoDef
     */
    public function setCondition($condition = true) {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCondition() {
        return $this->condition;
    }
}
