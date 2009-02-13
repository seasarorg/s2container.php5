<?php
/**
 * @S2Component('available' => false);
 */
class StandardDto {

    /**
     * アクセッサメソッド名とカラム名の対応を保持するスプール
     *
     * @var array
     */
    protected $__columns__ = array();

    /**
     * @var array
     */
    private static $__camel2bar__ = array();

    /**
     * Proxyメソッド
     */
    public function __call($name, $args) {
        $type = substr($name, 0, 3);
        if ($type == 'set') {
            $colName = $this->__getColName(substr($name, 3));
            $this->$colName = $args[0];
            return $args[0];
        } else if($type == 'get') {
            $colName = $this->__getColName(substr($name, 3));
            return $this->$colName;
        } else {
            throw new Exception('invalid method call. [' . $name . ']');
        }
    }

    /**
     * アクセッサ名からカラム名を決定し、カラム名のプロパティが存在していれば
     * その名前を返す。
     *
     * セッターが setAbcDef の場合、カラム名は、ABC_DEF、abc_def、Abc_Def のいずれかとなる。
     * セッターが setabcDef の場合、カラム名は、ABC_DEF、abc_def、abc_Def のいずれかとなる。
     * セッターが setAbc の場合、カラム名は、ABC、abc、Abc のいずれかとなる。
     * セッターが setabc の場合、カラム名は、ABC、abc のいずれかとなる。
     *
     * @throw Exception カラム名のプロパティが見つからなかった場合にスローされます。
     * @param string $key
     * @return string
     */
    protected function __getColName($key) {
        if (array_key_exists($key, $this->__columns__)) {
            return $this->__columns__[$key];
        }
        if (!array_key_exists($key, self::$__camel2bar__)) {
            $n = preg_replace('/([A-Z])/', '_\1', $key);
            if (0 === strpos($n, '_')) {
                $n = substr($n, 1);
            }
            self::$__camel2bar__[$key] = $n;
        }

        $name = self::$__camel2bar__[$key];
        $properties = get_object_vars($this);
        $ucName = strtoupper($name);
        if (array_key_exists($ucName, $properties)) {
            $this->__columns__[$key] = $ucName;
            return $ucName;
        }
        $lcName = strtolower($name);
        if (array_key_exists($lcName, $properties)) {
            $this->__columns__[$key] = $lcName;
            return $lcName;
        }
        if (array_key_exists($name, $properties)) {
            $this->__columns__[$key] = $name;
            return $name;
        }
        throw new Exception('property not found for [' . $key . ']');
    }
}
