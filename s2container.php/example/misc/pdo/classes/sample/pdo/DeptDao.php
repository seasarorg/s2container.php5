<?php
namespace sample\pdo;

/**
 * @S2Pdo('sample\pdo\SampleDto')
 */
class DeptDao {

    public function findAll() {
        return "select * from Dept";
    }
}
