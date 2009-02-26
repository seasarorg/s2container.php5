<?php
class SqlitePdo extends \PDO {
    public function __construct() {
        parent::__construct('sqlite:' . DB_DIR . '/sqlite.db');
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
