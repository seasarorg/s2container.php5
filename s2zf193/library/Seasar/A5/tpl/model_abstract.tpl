<?php
abstract class @@SUPER_CLASS@@ extends Zend_Db_Table_Abstract {
    public static $VALIDATORS = array('*' => array());
    public static $FILTERS    = array('*' => 'StringTrim');
    public static $OPTIONS    = array();

    public function getValidators() {
        return array_merge(self::$VALIDATORS, static::$VALIDATORS);
    }

    public function getFilters() {
        return array_merge(self::$FILTERS, static::$FILTERS);
    }

    public function getFilterInput($data = array()) {
        return new Zend_Filter_Input(self::getFilters(), self::getValidators(), $data, self::$OPTIONS);
    }

    public function getFields() {
        return static::$FILEDS;
    }

    public function getField($pname) {
        return $this->getFieldByPname($pname);
    }

    public function getFieldByPname($pname) {
        foreach(static::$FIELDS as $field) {
            if ($field['pname'] === $pname) {
                return $field;
            }
        }
    }

    public function getFieldByLname($lname) {
        foreach(static::$FIELDS as $field) {
            if ($field['lname'] === $lname) {
                return $field;
            }
        }
    }
}
