<?php

use \Exception;

class ErrorMessage extends Exception
{
    use MessageAwareTrait;
    
    private $data;

    public function __consturct($message, $code, array $data = [])
    {
        parent::__consturct($message, $code);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        $message = [
            'id' => null,
            'jsonrpc' => '2.0',
            'error' => [
                'code' => $this->getCode(),
                'message' => $this->getMessage()
            ]
        ];
        $data and ($message['error']['data'] = $data);
        $options = JSON_NUMERIC_CHECK|JSON_HEX_QUOT|JSON_HEX_APOS|JSON_HEX_TAG|JSON_HEX_AMP;        
        $payload = json_encode($message, $options);

        return $payload;   
    }
}