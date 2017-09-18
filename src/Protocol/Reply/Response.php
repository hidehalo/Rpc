<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\Json;
use Hidehalo\JsonRpc\Protocol\MessageInterface;

class Response implements ResponseInterface
{
    use RepAwareTrait;
    
    private $error;
    private $id;

    /**
     * @codeCoverageIgnore
     * @param $id
     * @param $result
     */
    public function __construct($id, $result)
    {
        $this->id = $id;
        $this->result = $result;
    }

    public static function create(array $attributes = [])
    {
        extract($attributes);

        return new self($id, $result);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'jsonrpc' => $this->getVersion(),
            'id' => $this->getId(),
            'result' => $this->getResult(),
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $message = [
            'jsonrpc' => '2.0',
            'id' => $this->id?:null,
            'result' => $this->result,
        ];
        $this->error and ($message['error'] = $this->error);
        $payload = JSON::encode($message);

        return $payload;
    }
}