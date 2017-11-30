<?php
namespace Hidehalo\JsonRpc\Protocol;

class Request implements RequestInterface
{
    use MessageAwareTrait;

    private $method;

    private $params;

    private $extras;

    /**
     * @codeCoverageIgnore
     * @param $method
     * @param $params
     * @param array $extras
     */
    public function __construct($method = null, $params = [], $extras = [])
    {
        $this->id = uniqid(mt_rand(111,999));
        $this->method = $method;        
        $this->params = $params;
        $this->extras = $extras;
    }

    public static function create(array $attributes = [])
    {
        extract($attributes);
        $req = new self();

        return $req->withMethod($method)->withParams($params)
            ->withExtras(isset($extras)? $extras : [])->withId(isset($id)? $id : null);
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
        $payload = json_encode($this->toArray());

        return $payload;
    }

    public function toArray()
    {
        $asArray = [
            'jsonrpc' => $this->getVersion(),
            'id' => $this->id,
        ];
        $this->params and ($asArray['method'] = $this->method);
        $this->params and ($asArray['params'] = $this->params);
        $this->extras and ($asArray['extras'] = $this->extras);

        return $asArray;
    }
}