<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use \Exception;
use Hidehalo\JsonRpc\Protocol\MessageInterface;

class ErrorResponse extends Exception implements MessageInterface
{
    use RepAwareTrait;
    
    private $data;

    /**
     * @codeCoverageIgnore
     * @param string $message
     * @param int $code
     * @param null $prev
     * @param $data
     */
    public function __construct($message, $code, $prev = null, $data)
    {
        parent::__construct($message, $code, $prev);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        $message = $this->toArray();
        $options = JSON_HEX_QUOT|JSON_HEX_TAG;
        $payload = json_encode($message, $options);

        return $payload;   
    }

    public function toArray()
    {
        $asArray = [
            'jsonrpc' => '2.0',
            'id' => null,
            'error' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage(),
            ]
        ];
        !is_null($this->data) and ($asArray['error']['data'] = $this->data);

        return $asArray;
    }
}