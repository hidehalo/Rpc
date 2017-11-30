<?php

namespace Hidehalo\JsonRpc\Protocol;

use SplQueue;
use Hidehalo\JsonRpc\Connection;
use Hidehalo\JsonRpc\Protocol\Json;

class BatchRequest
{
    private $buffer;
    private $queue;
    private $conn;

    /**
     * @codeCoverageIgnored
     * @param array $reqs
     */
    public function __construct($reqs = [])
    {
        $this->queue = new SplQueue();
        $this->buffer = [];
        if ($reqs) {
            foreach ($reqs as $req) {
                extract($req->toArray());
                $tmp = new Request($method, $params);
                $tmp = $tmp->withId($id);
                $this->queue->enqueue($tmp);
            }
        }
        // $this->conn = $conn;
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
     * TODO: Remove $this->conn->write(),it should be return payload
     * @return string
     */
    public function execute()
    {
        //TODO: remove
        $payload = $this->build();
        // $this->conn->write($payload);

        // return $this->conn->read();
        return $payload;
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

    public function __toString()
    {
        return $this->build();
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
        $payload = Json::encode($this->buffer);

        return $payload;
    }
}