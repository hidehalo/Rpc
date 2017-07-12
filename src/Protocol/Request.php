<?php
namespace JsonRpc\Protocol;

class Request implements RequestInterface
{
    use MessageAwareTrait;

    private $id;

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

    public function withMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function withParams($params)
    {
        $this->param = $params;

        return $this;
    }

    public function withExtras($extras) 
    {
        $this->extras = $extras;

        return $this;
    }

    public function __toString()
    {
        $payload = array_merge_recursive($this->extras, [
            'jsonrpc' => $this->getVersion(),
            'method' => $this->method,
            'id' => $this->id,
        ]);
        $this->params and ($payload['params'] = $this->params);

        return json_encode($payload);
    }

    public function toArray()
    {
        return [
            'jsonrpc' => $this->getVersion(),
            'method' => $this->method,
            'id' => $this->id,
            'params' => $this->params
        ];
    }
}