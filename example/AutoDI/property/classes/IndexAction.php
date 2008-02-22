<?php
class IndexAction {
    public $addLogic = 's2binding';
    public function execute() {
        $result = $this->addLogic->run(2,3);
    }
}
