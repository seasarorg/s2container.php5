<?php

class HelloImpl implements Hello{
    private $helloMessage_;

    public function setMessage($helloMessage) {
        $this->helloMessage_ = $helloMessage;
    }

    public function getMessage() {
        return $this->helloMessage_;
    }
}
?>