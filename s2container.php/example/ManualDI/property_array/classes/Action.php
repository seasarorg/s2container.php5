<?php
/**
 * @S2Component('autoBinding' => 'none')
 */
class Action {

    /**
     * @S2Binding('"seasar"')
     */
    public $name = null;

    /**
     * @S2Binding('2000 + 8')
     */
    public $year = null;

    /**
     * @S2Binding('ServiceA')
     */
    public $service = 'S2Binding ServiceA[]';

    public function execute() {
        $result = $this->service->add(2,3);
    }
}
