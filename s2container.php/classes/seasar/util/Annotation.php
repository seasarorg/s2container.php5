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
 * アノテーションを読み込むユーティリティクラスです。
 *
 * @copyright 2005-2008 the Seasar Foundation and the Others.
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 * @link      http://s2container.php5.seasar.org/
 * @version   SVN: $Id:$
 * @since     Class available since Release 2.0.0
 * @package   seasar.util
 * @author    klove
 */
namespace seasar::util;
class Annotation {

    /**
     * @var boolean
     */
    public static $CONSTANT = false;

    /**
     * @var boolean
     */
    public static $COMMENT  = true;

    /**
     * @var array
     */
    private static $spool = array();

    /**
     * アノテーションデータをスプールに登録します。
     *
     * @param string $annoKey
     * @param array  $annoArray
     * @return array
     */
    public static function put($annoKey, array $annoArray) {
        self::$spool[$annoKey] = $annoArray;
    }

    /**
     * アノテーションを取得します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return array
     */
    public static function get($reflection, $annotation) {
        if (self::$CONSTANT and self::hasConstantAnnotation($reflection, $annotation)) {
            return self::getConstantAnnotation($reflection, $annotation);
        }
        if (self::$COMMENT) {
            return self::getCommentAnnotation($reflection, $annotation);
        }
        throw new seasar::exception::AnnotationNotFoundException($reflection, $annotation);
    }

    /**
     * 定数アノテーションを取得します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return array
     */
    public static function getConstantAnnotation($reflection, $annotation) {
        self::validateReflection($reflection);
        $annoKey = self::constructKey($reflection, $annotation);
        if (isset(self::$spool[$annoKey])) {
            return self::$spool[$annoKey];
        }

        if ($reflection instanceof ReflectionProperty or
            $reflection instanceof ReflectionMethod) {
            $annotation = $reflection->getName() . '_' . $annotation;
            $reflection = $reflection->getDeclaringClass();
        }

        $annoArray = null;
        if ($reflection->hasConstant($annotation)) {
            $value = EvalUtil::formatArrayExpression(trim($reflection->getConstant($annotation)));
            $annoArray = EvalUtil::execute($value);
        }

        if (is_array($annoArray)) {
            self::$spool[$annoKey] = $annoArray;
        } else {
            throw new seasar::exception::AnnotationNotFoundException($reflection, $annotation);
        }
        return self::$spool[$annoKey];
    }

    /**
     * コメントアノテーションを取得します。
     * コメントアノテーションのフォーマットは、次の2つなります。
     *   (アノテーション名)(0個以上の空白)(PHP配列フォーマット値);
     *   (アノテーション名)(0個以上の空白)(PHP配列フォーマット値)(スペース)
     *   サンプル
     *     @Hoge(1, 2, 3);                 => array(1, 2, 3)
     *     @Huga ('a' => 1, 'b' => 2);     => array('a' => 1, 'b' => 2)
     *     @Foo(100)                       => array(100)
     *     @Bar ('a' => 1, 'b' => 2)       => array('a' => 1, 'b' => 2)
     *  次のケースでは、パースに失敗します。
     *    @Hoge("new Pdo() ")
     *    最後尾にセミコロンがないため、先頭から最短マッチで「) 」までが処置されます。
     *    閉じ括弧の後ろのスペースを削除するか、最後尾にセミコロンを付ける必要があります。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return array
     */
    public static function getCommentAnnotation($reflection, $annotation) {
        self::validateReflection($reflection);
        $annoKey = self::constructKey($reflection, $annotation);
        if (isset(self::$spool[$annoKey])) {
            return self::$spool[$annoKey];
        }

        $comment = self::formatCommentLine($reflection->getDocComment());
        $annoArray = null;
        $matches = array();
        if (preg_match("/$annotation\s*\((.*?)\);/iu", $comment, $matches) or
            preg_match("/$annotation\s*\((.*?)\)\s/iu", $comment, $matches)) {
            $value = EvalUtil::formatArrayExpression(trim($matches[1]));
            $annoArray = EvalUtil::execute($value);
        }

        if (is_array($annoArray)) {
            self::$spool[$annoKey] = $annoArray;
        } else {
            throw new seasar::exception::AnnotationNotFoundException($reflection, $annotation);
        }
        return self::$spool[$annoKey];
    }

    /**
     * アノテーションが存在するかどうかを返します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return boolean
     */
    public static function has($reflection, $annotation) {
        if (self::$CONSTANT) {
            $ret = self::hasConstantAnnotation($reflection, $annotation);
            if ($ret === true) {
                return true;
            } else if ($ret === false and self::$COMMENT === false) {
                return false;
            }
        }
        if (self::$COMMENT) {
            return self::hasCommentAnnotation($reflection, $annotation);
        }
        return false;
    }

    /**
     * 定数アノテーションが存在するかどうかを返します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return boolean
     */
    public static function hasConstantAnnotation($reflection, $annotation) {
        try {
            self::getConstantAnnotation($reflection, $annotation);
            return true;
        } catch (seasar::exception::AnnotationNotFoundException $e) {
            return false;
        }
    }

    /**
     * コメントアノテーションが存在するかどうかを返します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return boolean
     */
    public static function hasCommentAnnotation($reflection, $annotation) {
        try {
            self::getCommentAnnotation($reflection, $annotation);
            return true;
        } catch (seasar::exception::AnnotationNotFoundException $e) {
            return false;
        }
    }

    /**
     * コメントをフォーマットします。
     *
     * @param strint $commentLine
     * @return string
     */
    public static function formatCommentLine($commentLine) {
        $comments = preg_split('/[\n\r]/', $commentLine, -1, PREG_SPLIT_NO_EMPTY);
        $comment = ' ';
        $lineCount = count($comments);
        if ($lineCount == 0) {
            return '';
        } else if ($lineCount == 1) {
            $comment = substr(trim($comments[0]), 3, -2);
        } else {
            $atFound = false;
            $comment .= substr(trim($comments[0]), 3) . ' ';
            if (0 < strpos($comment, '@')) {
                $atFound = true;
            }
            for($i=1; $i<$lineCount-1; $i++) {
                if ($atFound === false) {
                    if (0 < strpos($comments[$i], '@')) {
                        $atFound = true;
                    }
                }
                if ($atFound === true) {
                    $comment .= substr(trim($comments[$i]), 1) . ' ';
                }
            }
            $last = substr(trim($comments[$lineCount-1]), 0, -2);
            if (0 < strlen($last)) {
                $comment .= substr($last, 1);
            }
        }
        return $comment;
    }

    /**
     * spoolに保存するための一意のキーを作成します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return string
     */
    public static function constructKey($reflection, $annotation) {
        if ($reflection instanceof ReflectionClass) {
            return $reflection->getName() . '::CLASS_' . $annotation;
        } else if ($reflection instanceof ReflectionMethod) {
            return $reflection->getDeclaringClass()->getName() . '::' . $reflection->getName() . '::METHOD_' . $annotation;
        } else {
            return $reflection->getDeclaringClass()->getName() . '::' . $reflection->getName() . '::PROPERTY_' . $annotation;
        }
    }

    /**
     * 対応しているReflectionクラスかどうかを検証します。
     *
     * @param mixed
     * @throw seasar::exception::AnnotationNotSupportedException
     */
    protected static function validateReflection($reflection) {
        if (!$reflection instanceof ReflectionClass and
            !$reflection instanceof ReflectionMethod and
            !$reflection instanceof ReflectionProperty) {
            throw new seasar::exception::AnnotationNotSupportedException('unsupported reflection.');
        }
    }
}
