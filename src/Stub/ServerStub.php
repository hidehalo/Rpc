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

     public function procedure($method, array $params)
     {
        return \call_user_func_array($method, $params);
     }

     /**
      * start batch response
      */
     public function batch()
     {
        return new BatchResponse();
     }
}