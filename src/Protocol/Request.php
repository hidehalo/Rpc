<?php
namespace Hidehalo\JsonRpc\Protocol;

class Request implements RequestInterface
{
    use MessageAwareTrait;

    private $method;

    private $params;

    private $extras;

    public function __construct($method, $params, $extras = [])
    {
        $this->id = uniqid(mt_rand(111,999));
        $this->method = $method;        
        $this->params = $params;
        $this->extras = $extras;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getExtras()
    {
        return $this->extras;
    }

    public function withMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function withParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function withExtras($extras) 
    {
        $this->extras = $extras;

        return $this;
    }

    public function __toString()
    {
        $payload = [
            'jsonrpc' => $this->getVersion(),
            'id' => $this->id,
            'method' => $this->method,
        ];
        $this->params and ($payload['params'] = $this->params);
        $this->extras and ($payload['extras'] = $this->extras);

        return json_encode($payload);
    }

    public function toArray()
    {
        return [
            'jsonrpc' => $this->getVersion(),
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params,
            'extras' => $this->extras,
        ];
    }
}