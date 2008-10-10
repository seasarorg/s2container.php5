<?php
class Action {
    private $name    = null;
    private $year    = null;
    private $service = null;

    /**
     * @S2Binding('strval("seasar")');
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * @S2Binding('2000 + 8');
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @S2Binding('Service');
     */
    public function setService(Service $service) {
        $this->service = $service;
    }

    public function execute() {
        $result = $this->service->add(2,3);
    }
}
