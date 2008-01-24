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
 * eval関数用のユーティリティクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar::util;
class EvalUtil {

    /**
     * EvalUtilの構築は許可されていません。
     */
    private function __construct(){}

    /**
     * Expressionをフォーマットして実行します。
     *
     * @param string $src
     * @param array $context
     * @return mixed
     */
    public static function formatExecute($src, array $context = array()) {
        return self::execute(self::formatExpression($src), $context);
    }

    /**
     * Expressionをeval関数で実行します。
     *
     * @param string $src
     * @param array $context
     * @return mixed
     */
    public static function execute($src, array $context = array()) {
        foreach ($context as $key => $val) {
            $$key = $val;
        }

        if (seasar::Config::$DEBUG_EVAL) {
            seasar::log::S2Logger::getInstance(__CLASS__)->debug($src, __METHOD__);
        }
        return eval($src);
    }

    /**
     * Expressionをフォーマットします。
     *
     * @param string $src
     * @return string
     */
    public static function formatExpression($src) {
        $src = trim($src);
        if (!preg_match('/^return\s/i', $src)) {
            $src = 'return ' . $src;
        }
        if (!preg_match('/;$/i', $src)) {
            $src .= ';';
        }
        return $src;
    }

    /**
     * Expressionをarrayとしてフォーマットします。
     *
     * @param string $src
     * @return string
     */
    public static function formatArrayExpression($src) {
        if (!preg_match('/^array/', $src)) {
            $src = 'array(' . $src . ')';
        }
        return self::formatExpression($src);
    }
}
