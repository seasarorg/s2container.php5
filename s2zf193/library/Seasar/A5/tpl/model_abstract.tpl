<?php
class Model_DbTable_Abstract extends Zend_Db_Table_Abstract {

    public function getValidators() {
        return static::$VALIDATORS;
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
