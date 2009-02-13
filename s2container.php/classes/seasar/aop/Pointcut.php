<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2009 the Seasar Foundation and the Others.            |
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
 * MethodInterceptorがどこに適用されるのかをあらわすクラスです。
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop
 * @author    klove
 */
namespace seasar\aop;
final class Pointcut {
    private $methodNames = null;
    private $methodNamePattern = null;
    private $isRegex = false;

    /**
     * Pointcutを構築します。
     * @param mixed $target Pointcutを指定する正規表現文字列の配列、またはReflectionClass。
     *                      \ReflectionClassの場合は、そのクラスが実装しているインターフェースのメソッドがPointcutとなります。
     */
    public function __construct($target) {
        if ($target instanceof \ReflectionClass) {
            $this->setupMethodNames($target);
        } else if (is_string($target)) {
            $this->methodNamePattern = $target;
            $this->isRegex = true;
        } else {
            throw new \InvalidArgumentException('expected \ReflectionClass|string.');
        }
    }

    /**
     * 指定されたメソッドにMethodInterceptorが適用されるかどうかを返します。
     * @param string $methodName
     * @return boolean
     */
    public function isApplied($methodName) {
        if ($this->isRegex) {
            if (preg_match($this->methodNamePattern, $methodName)) {
                return true;
            }
        } else {
           if (in_array($methodName, $this->methodNames)) {
               return true;
           }
        }
        return false;
    }

    /**
     * Pointcutの指定がなかった場合に、ターゲットクラスのAbstractメソッドを取得しPointcutとします。または、
     * ターゲットクラスがAbstractメソッドをもっていなかった場合は、そのクラスの実装メソッドをPointcutとします。
     * set、get、isで名前が始まるメソッドはPointcutに含まれません。
     *
     * @param \ReflectionClass $targetClass
     * @return array
     */
    private function setupMethodNames(\ReflectionClass $targetClass) {
        if (!$targetClass->isAbstract() and
            !$targetClass->isInterface() and
            count($targetClass->getInterfaces()) === 0) {
            $methods = \seasar\util\ClassUtil::getDeclaringMethods($targetClass);
        } else {
            $methods = \seasar\util\ClassUtil::getImplementMethods($targetClass);
        }
        $this->methodNames = array();
        foreach($methods as $method) {
            $methodName = $method->getName();
            if (preg_match('/^(set|get|is)[A-Z].*$/', $methodName)) {
                \seasar\log\S2Logger::getInstance(__NAMESPACE__)->debug('ignore method [' . $methodName . ']', __METHOD__);
                continue;
            }
            $this->methodNames[] = $methodName;
        }
    }
}
