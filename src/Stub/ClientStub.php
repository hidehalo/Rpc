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

    /**
     * start batch requests
     */
    public function batch(Closure $closure)
    {
        $batch = new BatchRequest();
        if ($closure) {
            return $closure($batch);
        }

        return $batch;
    }

    public function __call($name, $arguments = [])
    {
        $req = $this->procedure($name, $arguments);
        $this->conn->write($req);
        $res = $this->conn->read();

        return JSON::parseResponse($req);
    }
}