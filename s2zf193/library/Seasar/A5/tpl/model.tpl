<?php
/**
 * @S2Component('autoBinding' => 'none')
 */
class @@CLASS_NAME@@ extends @@PARENT_CLASS_NAME@@ {

    const PNAME = @@PNAME@@;
    const LNAME = @@LNAME@@;
    const COMMENT = @@COMMENT@@;

    public static $VALIDATORS = @@VALIDATORS@@;

    public static $FIELDS = @@FIELDS@@;

    protected $_name = @@TABLE_NAME@@;
    protected $_primary = @@PRIMARY_KEYS@@;
    protected $_sequence = @@SEQUENCE@@;

    protected $_dependentTables = @@DEPENDENT@@;

    protected $_referenceMap = @@REFERENCE@@;

@@COMMENT_PHP_SOURCE@@

}
