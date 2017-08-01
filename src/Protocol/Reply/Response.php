<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\MessageInterface;

class Response implements MessageInterface
{
    use RepAwareTrait;
    
    private $error;
    private $id;

    public function __construct($id, $result)
    {
        $this->id = $id;
        $this->result = $result;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function toArray()
    {
        return [
            'jsonrpc' => $this->getVersion(),
            'id' => $this->getId(),
            'result' => $this->getResult(),
        ];
    }


    public function __toString()
    {
        $options = JSON_HEX_QUOT|JSON_HEX_TAG;
        $message = [
            'jsonrpc' => '2.0',
            'id' => $this->id?:null,
            'result' => $this->result,
        ];
        $this->error and ($message['error'] = $this->error);
        $payload = json_encode($message, $options);

        return $payload;
    }
}