<?php
namespace Hidehalo\JsonRpc\Stub;

class ServerStub implements StubInterface
{
    /**
     * @coverageIgnored
     * @param Connection $connection
     */
     public function __construct(Connection $connection)
     {
         $this->conn = $connection;
     }
 
}