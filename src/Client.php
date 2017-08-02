<?php

namespace Hidehalo\JsonRpc;

use Hidehalo\JsonRpc\Connection;

class Client
{
    /**
     * @coverageIgnored
     * @param $gateway
     * @param $registerPath
     */
    public function __construct($gateway, $registerPath)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param $name
     * @return ClientStub
     */
    public function getStub($name)
    {
        return new ClientStub($name, new Connection($this->gateway));
    }
}
