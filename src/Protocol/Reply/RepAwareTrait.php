<?php

namespace JsonRpc\Protocol\Reply;

trait RepAwareTrait
{
    use MessageAwareTrait;

    private $result;

    public function getResult()
    {
        return $this->result;
    }
}