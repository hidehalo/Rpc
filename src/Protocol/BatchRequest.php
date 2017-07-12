<?php

use SplStack;

class BatchRequest
{
    public function __consturct()
    {
        $this->callStack = new SplStack();
    }

    public function __call($method, $params)
    {

    }

    public function build()
    {

    }
}