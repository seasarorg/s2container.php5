<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/S2Container.php');

class Action {
    public $service;
}

class Service {
    public $dao;
}

class Dao {
    private $pdo;
    public function setPdo(PDO $pdo) {
        $this->pdo = $pdo;
    }
}

s2component('PDO')->construct(function(){
    return new PDO('sqlite::memory:');
});

var_dump(s2app::get('Action'));
