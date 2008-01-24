<?php
class Action {
    private $service = null;

    public function __construct($name, $year, array $service) {
        $this->name    = $name;
        $this->year    = $year;
        $this->service = $service;
    }

    public function execute() {
        $result = $this->service->add(2,3);
    }
}
