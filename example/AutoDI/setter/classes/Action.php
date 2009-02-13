<?php
class Action {
    private $service = null;

    public function setService(Service $service) {
        $this->service = $service;
    }

    public function execute() {
        $result = $this->service->add(2,3);
    }
}
