<?php
/**
 * StrictInterceptorで使用されるアノテーションのファクトリクラス
 */
class StrictAnnotationFactory {
    /**
     * @var array
     */
    private static $spool = array();

    /**
     * メソッドに付いているコメントアノテーションを読み込みます。
     * アノテーション情報はシングルトンとして扱います。
     *
     * @param \ReflectionMethod $method
     * @return array
     */
    public static function create(\ReflectionMethod $method) {
        $key =self::getKey($method);
        if (!isset(self::$spool[$key])) {
            self::$spool[$key] = self::read($method);
        }
        return self::$spool[$key];
    }

    /**
     * メソッドに付いているコメントアノテーションを読み込みます。
     * コメントアノテーションは、@param、@returnが対象となります。
     * 例)
     *    @param int $valA         array('param', array('int', 'int')
     *    @param int $valB    =>         'return', 'string')
     *    @return string
     *
     * @param \ReflectionMethod $method
     * @return array
     */
    public static function read(\ReflectionMethod $method) {
        $comment = $method->getDocComment();
        $annoInfo = array();
        $matches = array();
        if (preg_match_all('/@param\s+([^\s]+)/iu', $comment, $matches)) {
            $annoInfo['param'] = $matches[1];
        }
        $matches = array();
        if (preg_match_all('/@return\s+([^\s]+)/iu', $comment, $matches)) {
            $annoInfo['return'] = $matches[1][0];
        }
        return $annoInfo;
    }

    /**
     * メソッドごとの一意のキーを返します。
     *
     * @param \ReflectionMethod $method
     * @return string
     */
    private static function getKey(\ReflectionMethod $method) {
        return $method->getDeclaringClass()->getName() . '\\' . $method->getName();
    }
}
