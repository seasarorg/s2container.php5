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
 * モック用のMethodInterceptorです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar\aop\interceptor;
class MockInterceptor implements \seasar\aop\MethodInterceptor {

    /**
     * コメントアノテーションで使用されるタグ名です。
     */
    const ANNOTATION = '@S2Mock';

    /**
     * コメントアノテーションで値を返す場合のキー名です。
     */
    const ANNOTATION_RETURN = 'return';

    /**
     * コメントアノテーションで例外をスローする場合のキー名です。
     */
    const ANNOTATION_THROWABLE = 'throw';

    /**
     * @var array メソッドの戻り値をメソッドごとに保持します。
     */
    private $returnValueMap = array();

    /**
     * @var array メソッド呼び出し時にスローする例外をメソッドごとに保持します。
     */
    private $throwableMap   = array();

    /**
     * 指定したメソッドに対する戻り値を設定します。
     * @param string method name
     * @param value
     */
    public function setReturnValue($methodName, $returnValue) {
        $this->returnValueMap[$methodName] = $returnValue;
    }

    /**
     * 指定したメソッドを呼び出したときに、 スローされる例外を設定します。
     * @param string method name
     * @param Exception 
     */
    public function setThrowable($methodName, \Exception $throwable) {
        $this->throwableMap[$methodName] = $throwable;
    }

    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(\seasar\aop\MethodInvocation $invocation) {
        $methodName = $invocation->getMethod()->getName(); 

        if (array_key_exists($methodName, $this->throwableMap)) {
            throw $this->throwableMap[$methodName];
        }
        else if (array_key_exists($methodName, $this->returnValueMap)) {
            return $this->returnValueMap[$methodName];
        }
        else if (\seasar\util\Annotation::has($invocation->getMethod(), self::ANNOTATION)) {
            $annoValue = \seasar\util\Annotation::get($invocation->getMethod(), self::ANNOTATION);
            if (isset($annoValue[self::ANNOTATION_RETURN])) {
                return \seasar\util\EvalUtil::formatExecute($annoValue[self::ANNOTATION_RETURN]);
            } else if (isset($annoValue[self::ANNOTATION_THROWABLE])) {
                throw \seasar\util\EvalUtil::formatExecute($annoValue[self::ANNOTATION_THROWABLE]);
            } else {
                \seasar\log\S2Logger::getInstance(__CLASS__)->info('proceed though mock aspect found.', __METHOD__);
                return $invocation->proceed();
            }
        }
        else {
            \seasar\log\S2Logger::getInstance(__CLASS__)->info('proceed though mock interceptor aspected.', __METHOD__);
            return $invocation->proceed();
        }
    }
}
