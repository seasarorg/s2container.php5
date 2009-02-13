<?php
/**
 * @S2Component('namespace' => 'pdo');
 */
class StandardPdo extends \PDO {
    public static $DSN = null;
    public static $USERNAME = null;
    public static $PASSWORD = null;

    public function __construct() {
        if (self::$DSN === null) {
            throw new Exception('dsn is not set.');
        }
        parent::__construct(self::$DSN, self::$USERNAME, self::$PASSWORD);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
