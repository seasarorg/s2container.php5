<?php
class Action {
    public $service = 'S2Binding Service';
    public function execute() {
        $result = $this->service->add(2,3);
    }
}
