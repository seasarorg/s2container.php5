<?php
class CdDao {
    public $sqlitePdo = null;
    public function findAll() {
        $stmt = $this->sqlitePdo->prepare('select * from CD');
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
