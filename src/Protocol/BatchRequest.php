<?php

namespace Hidehalo\JsonRpc\Protocol;

use SplQueue;
use Hidehalo\JsonRpc\Connection;

class BatchRequest
{
    private $buffer;
    private $queue;
    private $conn;

    /**
     * @codeCoverageIgnored
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->queue = new SplQueue();
        $this->buffer = [];
        $this->conn = $conn;
    }

    /**
     * @param $method
     * @param $params
     * @return $this
     */
    public function __call($method, $params)
    {
        $req = new Request($method, $params);
        $this->queue->enqueue($req);

        return $this;
    }

    /**
     * @return string
     */
    public function execute()
    {
        $payload = $this->build();
        $this->conn->write($payload);

        return $this->conn->read();
    }

    /**
     * @return array
     */
    public function getIds()
    {
        /**
         * @var MessageInterface $message
         */
        $ret = [];
        foreach ($this->queue as $message) {
            $ret[] = $message->getId();
        }

        return $ret;
    }

    /**
     * @return string
     */
    private function build()
    {
        while (!$this->queue->isEmpty()) {
            $req = $this->queue->dequeue();
            $this->buffer[] = $req->toArray();
        }
        $payload = json_encode($this->buffer);

        return $payload;
    }
}