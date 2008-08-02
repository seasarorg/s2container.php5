<?php
// +----------------------------------------------------------------------+
// | Copyright 2005-2008 the Seasar Foundation and the Others.            |
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
 * トレース出力用のMethodInterceptorです。
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.aop.interceptor
 * @author    klove
 */
namespace seasar::aop::interceptor;
class TraceInterceptor implements seasar::aop::MethodInterceptor {
    /**
     * @see MethodInterceptor::invoke()
     */
    public function invoke(seasar::aop::MethodInvocation $invocation) {
        $buf = $invocation->getTargetClass($invocation)->getName()
             . '->' . $invocation->getMethod()->getName() . '(';
        $args = $invocation->getArguments();
        if (is_array($args)) {
            $argsTmp = array();
            foreach ($args as $arg) {
                $argsTmp[] = seasar::util::StringUtil::mixToString($arg);
            }
            $buf .= implode(',',$argsTmp);
        }
        $buf .= ')';
        $ret = null;
        $cause = null;
        seasar::log::S2Logger::getInstance(__CLASS__)->info('BEGIN ' . $buf, __METHOD__);
        try {
            $startTime = microtime(true);
            $ret = $invocation->proceed();
            $stopTime = microtime(true);
            $execTime = $stopTime - $startTime;
            $buf .= ' : ' . seasar::util::StringUtil::mixToString($ret) . ' : ' . $execTime;
        } catch (Exception $t) {
            $buf .= ' Exception : ' . get_class($t) . ' : ' . $t->getMessage();
            $cause = $t;
        }
        seasar::log::S2Logger::getInstance(__CLASS__)->info('END   ' . $buf, __METHOD__);
        if ($cause == null) {
            return $ret;
        } else {
            throw $cause;
        }
    }
}
