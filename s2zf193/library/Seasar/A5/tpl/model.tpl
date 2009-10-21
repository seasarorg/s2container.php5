<?php
/**
 * @S2Component('autoBinding' => 'none')
 */
class @@CLASS_NAME@@ extends @@SUPER_CLASS@@ {

    const PNAME = @@PNAME@@;
    const LNAME = @@LNAME@@;
    const COMMENT = @@COMMENT@@;

    public static $FIELDS = @@FIELDS@@;

    public static $FILTERS = @@FILTERS@@;

    public static $VALIDATORS = @@VALIDATORS@@;

    protected $_name = @@TABLE_NAME@@;
    protected $_primary = @@PRIMARY_KEYS@@;
    protected $_sequence = @@SEQUENCE@@;

    protected $_dependentTables = @@DEPENDENT@@;

    protected $_referenceMap = @@REFERENCE@@;

@@COMMENT_PHP_SOURCE@@

}
