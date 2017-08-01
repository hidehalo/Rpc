<?php

namespace Hidehalo\JsonRpc\Stub\Client\ClientStubInterface;

interface ClientStubInterface
{
    public function import();

    public function procedure();

    public function encode();

    public function send();
}