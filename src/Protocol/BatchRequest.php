<?php
namespace JsonRpc\Protocol;

use SplQueue;

class BatchRequest
{
    private $buffer;

    public function __consturct()
    {
        $this->queue = new SPlQueue();
        $this->buffer = '';
    }

    public function __call($method, $params)
    {
        $req = new Request($method, $params);
        $this->queue->enqueue($req);
    }

    public function build()
    {
        $reqs = $this->queue;
        foreach ($reqs as $req) {
            $this->buffer .= (string) $req;
        }
        $payload = $this->buffer;

        return $payload;
    }
}