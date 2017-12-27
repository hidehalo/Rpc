<?php
namespace Hidehalo\JsonRpc\Stub;

use Hidehalo\JsonRpc\Connection;
use Hidehalo\JsonRpc\Protocol\Json;
use Hidehalo\JsonRpc\Protocol\Request;
use Hidehalo\JsonRpc\Protocol\BatchRequest;

class ClientStub
{
    protected $service;
    protected $conn;
    
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

    public function procedure($payload)
    {
        $this->conn->write($payload);
        $res = $this->conn->read();
        
        return Json::parseResponse($res);  
    }

    /**
     * start batch requests
     */
    public function batch()
    {
        $batch = new BatchRequest([], $this->service);

        return $batch;
    }

    public function __call($name, $arguments = [])
    {
        $req = new Request($name, $arguments, ['service' => $this->service]);

        return (string) $req;
    }
}