<?php

namespace JsonRpc\Protocol;

use SplQueue;

class BatchRequest
{
    private $buffer;
    private $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
        $this->buffer = [];
    }

    public function __call($method, $params)
    {
        $req = new Request($method, $params);
        $this->queue->enqueue($req);

        return $this;
    }

    public function build()
    {
        $reqs = $this->queue;
        foreach ($reqs as $req) {
            $this->buffer[] = $req->toArray();
        }
        $payload = json_encode($this->buffer);

        return $payload;
    }
}