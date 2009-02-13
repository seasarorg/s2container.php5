<?php
class Action {

    /**
     * @S2Binding('strval("seasar")');
     */
    public $name = null;

    /**
     * @S2Binding('2000 + 8');
     */
    public $year = null;

    /**
     * @S2Binding('Service');
     */
    public $service = null;

    public function execute() {
        $result = $this->service->add(2,3);
    }
}
