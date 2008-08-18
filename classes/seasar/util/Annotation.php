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
        self::validateReflection($reflection);
        $annoKey = self::constructKey($reflection, $annotation);
        if (isset(self::$spool[$annoKey])) {
            return self::$spool[$annoKey];
        }

        $annoArray = null;
        if (self::$CONSTANT) {
            $annoArray = self::getConstantAnnotation($reflection, $annotation);
        }
        if (self::$COMMENT and $annoArray === null) {
            $annoArray = self::getCommentAnnotation($reflection, $annotation);
        }
        if (is_array($annoArray)) {
            self::$spool[$annoKey] = $annoArray;
        } else {
            throw new seasar::exception::AnnotationNotFoundException($reflection, $annotation);
        }
        return self::$spool[$annoKey];
    }

    /**
     * 定数アノテーションを取得します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return array
     */
    public static function getConstantAnnotation($reflection, $annotation) {
        if (!$reflection instanceof ReflectionClass) {
            $reflection = $reflection->getDeclaringClass();
        }
        if ($reflection->hasConstant($annotation)) {
            $value = EvalUtil::formatArrayExpression(trim($reflection->getConstant($annotation)));
            return EvalUtil::execute($value);
        }
        return null;
    }

    /**
     * コメントアノテーションを取得します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return array
     */
    public static function getCommentAnnotation($reflection, $annotation) {
        if (!self::hasCommentAnnotation($reflection, $annotation)) {
            return null;
        }
        $matches = array();
        $regex = "/$annotation\((.*?)\)/siu";
        $comment = self::formatCommentLine($reflection->getDocComment());
        if (preg_match($regex, $comment, $matches)) {
            $value = EvalUtil::formatArrayExpression(trim($matches[1]));
            return EvalUtil::execute($value);
        }
        return null;
    }

    /**
     * アノテーションが存在するかどうかを返します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return boolean
     */
    public static function has($reflection, $annotation) {
        $annoKey = self::constructKey($reflection, $annotation);
        if (isset(self::$spool[$annoKey])) {
            return true;
        }

        $has = false;
        if (self::$CONSTANT) {
            $has = self::hasConstantAnnotation($reflection, $annotation);
        }
        if (self::$COMMENT) {
            $has = self::hasCommentAnnotation($reflection, $annotation);
        }
        return $has;
    }

    /**
     * 定数アノテーションが存在するかどうかを返します。
     *
     * @param ReflectionClass|ReflectionMethod|ReflectionProperty $reflection
     * @param strint $annotation
     * @return boolean
     */
    public static function hasConstantAnnotation($reflection, $annotation) {
        if ($reflection instanceof ReflectionClass) {
            return $reflection->hasConstant($annotation);
        } else {
            return $reflection->getDeclaringClass()->hasConstant($annotation);
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
        return preg_match("/$annotation\(.*?\)/siu", $reflection->getDocComment()) === 0 ? false : true;
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
        if ($lineCount == 1) {
            $comment = substr(trim($comments[0]), 3, -2);
        } else {
            $comment .= substr(trim($comments[0]), 3) . ' ';
            for($i=1; $i<$lineCount-1 ; $i++) {
                $comment .= substr(trim($comments[$i]), 1) . ' ';
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
