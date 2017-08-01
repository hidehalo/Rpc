<?php

namespace Hidehalo\JsonRpc;

use Hidehalo\JsonRpc\Connection;

class Client
{
    /**
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

    /**
     * @return string
     */
    public function testConn()
    {
        $conn = new Connection($this->gateway);
        $conn->write('hello');

        return $conn->read();
    }
}
