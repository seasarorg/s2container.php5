<?php
/**
 * @S2Component('namespace' => 'pdo_b');
 */
class SqliteBPdo extends PDO {
    public function __construct() {
        parent::__construct('sqlite:' . ROOT_DIR . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'sqlite_b.db');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
