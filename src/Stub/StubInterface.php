<?php
namespace Hidehalo\JsonRpc\Stub;

interface StubInterface
{
    public function procedure($method, array $params);
}