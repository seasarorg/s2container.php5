<?php
namespace sample;
class Service {
    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    public function a($a, $b) {
        return 1;
    }

    /**
     * @param int $a
     * @return int
     */
    public function b($a, $b) {
        return 1;
    }

    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    public function c($a, $b) {
        return 'a';
    }

    /**
     * @param int $a
     * @param int $b
     * @return XXX
     */
    public function d($a, $b) {
        return 'a';
    }

    /**
     * @param int|numeric $a
     * @param int $b
     * @return mixed
     */
    public function e($a, $b) {
    }

    /**
     * @param Hoge
     */
    public function f($a) {
    }

    /**
     * @param array $a
     * @return DateTime|Hoge
     */
    public function g(array $a) {
        return new DateTime;
    }

}
