<?php
require_once(dirname(dirname(__FILE__)) . '/example.inc.php');
use seasar\container\S2ApplicationContext as s2app;

class Service {}

class Action {
    public function setService(Service $service) {
        $this->service = $service;
    }
}

$action = s2app::get('Action');
var_dump($action);


