<?php
namespace JsonRpc\Protocol\Reply;

class Response implements MessageInterface
{
    use RepAwareTrait;
    
    private $error;

    public function __construct($id, $result)
    {
        $this->id = $id;
        $this->result = $result;
        $this->error = $error;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getResult()
    {
        return $this->result;
    }


    public function __toString()
    {
        $options = JSON_NUMERIC_CHECK|JSON_HEX_QUOT|JSON_HEX_APOS|JSON_HEX_TAG|JSON_HEX_AMP;
        $message = [
            'id' => $this->id?:null,
            'result' => $this->$result,
            'jsonrpc' => '2.0'
        ];
        $this->error and ($message['error'] = $this->error);
        $payload = json_encode($message, $options);

        return $payload;
    }
}