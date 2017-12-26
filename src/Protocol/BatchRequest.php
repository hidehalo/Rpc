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
    protected $service;

    /**
     * Constructor
     * 
     * @codeCoverageIgnored
     * @param array $reqs "Array of Request"
     * @param string $service "Default Service"
     */
    public function __construct(array $reqs = [], $service = '')
    {
        $this->service = $service;
        $this->queue = new SplQueue();
        $this->buffer = [];
        if ($reqs) {
            foreach ($reqs as $req) {
                $this->queue->enqueue($req);
            }
        }
    }

    /**
     * @param $method
     * @param $params
     * @return $this
     */
    public function __call($method, $params)
    {
        $extras = [];
        if ($this->service) {
            $extras = [
                'service' => $this->service
            ];
        }
        $req = new Request($method, $params, $extras);
        $this->queue->enqueue($req);

        return $this;
    }

    /**
     * TODO: Remove $this->conn->write(),it should be return payload
     * @return string
     */
    public function execute()
    {
        $payload = $this->build();
       
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

    public function getReqs()
    {
        return $this->queue;
    }

    public function _notify()
    {
        $notification = new Request();
        $notification = $notification->withId(null);
        $this->queue->enqueue($notification);

        return $this;
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
        $this->buffer = [];

        return $payload;
    }
}