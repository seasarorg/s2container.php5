<?php
class Service {
    public function add($a, $b) {
        print __CLASS__ . ' called.' . PHP_EOL;
        return $a + $b;
    }
}
