<?php
class Action {
    public $service = 'S2Binding ServiceA[]';
    public function execute() {
        $result = $this->service->add(2,3);
    }
}
