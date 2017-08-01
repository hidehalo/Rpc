<?php

namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\MessageAwareTrait;

trait RepAwareTrait
{
    use MessageAwareTrait;

    private $result;

    public function getResult()
    {
        return $this->result;
    }
}