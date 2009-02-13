<?php
class Action {
    private $name    = null;
    private $year    = null;
    private $service = null;

    public function setYear($year) {
        $this->year = $year;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setService(Service $service) {
        $this->service = $service;
    }

    public function execute() {
        $result = $this->service->add(2,3);
    }
}
