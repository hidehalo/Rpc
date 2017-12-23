<?php
namespace Hidehalo\JsonRpc\Stub;

class ServerStub
{
    /**
     * @coverageIgnored
     * @param Connection $connection
     */
     public function __construct(Connection $connection)
     {
         $this->conn = $connection;
     }

     /**
      * start batch response
      */
     public function batch()
     {
        return new BatchResponse();
     }
}