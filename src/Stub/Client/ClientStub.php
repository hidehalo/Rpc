<?php

namespace Hidehalo\JsonRpc;

use Hidehalo\JsonRpc\Connection;
use Hidehalo\JsonRpc\Protocol\BatchRequest;

class ClientStub
{
    private $rf;
    private $service;

    /**
     * @coverageIgnored
     * @param $service
     * @param \Hidehalo\JsonRpc\Connection $connection
     */
    public function __construct($service, Connection $connection)
    {
        $this->rf = new \ReflectionClass($service);
        $this->service = $service;
        $this->conn = $connection;
    }

    function procedure($name, $arguments)
    {
        $refParams  = $this->rf->getMethod($name)->getParameters();
        foreach ($refParams as $i => $param) {
            if ($param->isArray() and !is_array($arguments[$i])) {
                throw new \Exception(sprintf("parameter #%d %s need array", $i, $param->getName()));
            } elseif (!$param->isOptional() and !isset($arguments[$i])) {
                throw new \Exception(sprintf("parameter #%d %s is required", $i, $param->getName()));
            }
        }

        $payload = '';

        return $payload;
    }

    function batch()
    {
        return new BatchRequest($this->conn);
    }

    function __call($name, $arguments)
    {
        $payload = $this->procedure($name, $arguments);
        $this->conn->write($payload);

        return $this->conn->read();
    }
}