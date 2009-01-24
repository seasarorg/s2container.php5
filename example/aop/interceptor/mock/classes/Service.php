<?php
class Service {
    /**
     * @S2Mock('return' => '10');
     */
    public function add($a, $b) {
        print __CLASS__ . ' called.' . PHP_EOL;
        return $a + $b;
    }

    /**
     * @S2Mock('throw' => 'new \seasar\exception\NotYetImplementedException("mock exception")');
     */
    public function sub($a, $b) {
        print __CLASS__ . ' called.' . PHP_EOL;
    }
}
