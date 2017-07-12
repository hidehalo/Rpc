<?php

class Request
{
    const VERSION = '2.0';
     /**
     * Request ID
     *
     * @access private
     * @var mixed
     */
    private $id;

    /**
     * Method name
     *
     * @access private
     * @var string
     */
    private $method;

    /**
     * Method arguments
     *
     * @access private
     * @var array
     */
    private $params;

    /**
     * Additional request attributes
     *
     * @access private
     * @var array
     */
    private $extras;

   /**
    *
    */
    public function __construct($method, $params, $extras = [])
    {
        $this->id = uniqid(mt_rand(111,999));
        $this->method = $method;        
        $this->params = $params;
        $this->extras = $extras;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        $payload = array_merge_recursive($this->extras, [
            'jsonrpc' => static::VERSION,
            'method' => $this->method,
            'id' => $this->id,
        ]);

        if (!empty($this->params)) {
            $payload['params'] = $this->params;
        }

        return json_encode($payload);
    }
}