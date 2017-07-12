<?php
namespace JsonRpc\Protocol\Reply;

class BatchResponse implements MessageInterface
{
    private $replies;
    private $buffer;

    public function __construct(array $replies)
    {
        $this->replies = $replies;
    }

    public function __toString()
    {
       foreach ($this->replies as $reply) {
            $this->buffer[] = $reply->toArray();
       }
       $options = JSON_NUMERIC_CHECK|JSON_HEX_QUOT|JSON_HEX_APOS|JSON_HEX_TAG|JSON_HEX_AMP;     

       return json_encode($this->buffer, $options);
    }

    public function toArray()
    {
        $ret = [];
        foreach ($this->replies as $reply) {
            $ret[] = $reply->toArray();
        }  

        return $ret;
    }
}