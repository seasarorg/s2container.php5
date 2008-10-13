<?php
/**
 * @S2Component('available' => false);
 */
class PdoStandardModel {

    /**
     * アクセッサメソッド名とカラム名の対応を保持するスプール
     *
     * @var array
     */
    private $__columns__ = array();

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
     * @throw Exception カラム名のプロパティが見つからなかった場合にスローされます。
     * @param string $key
     * @return string
     */
    private final function __getColName($key) {
        if (array_key_exists($key, $this->__columns__)) {
            return $this->__columns__[$key];
        }
        $name = preg_replace('/([A-Z])/', '_\1', $key);
        $name = substr($name, 1);
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
