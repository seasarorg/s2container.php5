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
 * 文字列操作に関するユーティリティクラスです。
 *
 * @copyright 2005-2009 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 1.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar\util;
class StringUtil {

    /**
     * StringUtilの構築は許可されていません。
     */
    private function __construct(){}

    /**
     * 文字列の先頭1文字を小文字に変更します。
     *
     * @param string $value
     * @return string
     */
    public static function lcfirst($value) {
        return strtolower(substr($value, 0, 1)) . substr($value, 1);
    }

    /**
     * mixedの値を文字列にします。
     *
     * @param mixed $val
     * @return string
     */
    public static function mixToString($val) {
        if (is_array($val)) {
            $c = count($val);
            return "array($c)";
        } else if (is_object($val)){
            $name = get_class($val);
            $id = spl_object_hash($val);
            return "object[$name#$id]";
        } else if (is_bool($val)){
            if ($val) {
                return 'true';
            } else {
                return 'false';
            }
        } else if (is_null($val)){
            return 'null';
        } else {
            return (string)$val;
        }
    }
}
