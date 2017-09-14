<?php
namespace Hidehalo\JsonRpc;

use Hidehalo\JsonRpc\Connection;
use Hidehalo\JsonRpc\Protocol\Request;
use Hidehalo\JsonRpc\Protocol\BatchRequest;

class ClientStub implements StubInterface
{
    private $service;

    /**
     * @coverageIgnored
     * @param $service
     * @param Connection $connection
     */
    public function __construct($service, Connection $connection)
    {
        $this->service = $service;
        $this->conn = $connection;
    }

    public function procedure($method, array $params = [])
    {
        //TODO: 1. build request 2. return payload
        $request = new Request($method, $params, ['service' => $this->service]);

        return (string) $request;
    }

    public function batch()
    {
        return new BatchRequest($this->conn);
    }

    public function __call($name, $arguments = [])
    {
        $reqpayload = $this->procedure($name, $arguments);
        $this->conn->write($reqPayload);
        $resPayload = $this->conn->read();

        return JSON::parseResponse($reqPayload);
    }
}