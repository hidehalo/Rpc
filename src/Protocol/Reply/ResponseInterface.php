<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\MessageInterface;

interface ResponseInterface extends MessageInterface
{
    public function getResult();
}