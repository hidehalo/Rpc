<?php

namespace Hidehalo\JsonRpc\Test\Protocol;

use Hidehalo\JsonRpc\Protocol\MessageAwareTrait;
use Hidehalo\JsonRpc\Protocol\MessageInterface;

class MessageStub implements MessageInterface
{
    use MessageAwareTrait;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return '';
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}