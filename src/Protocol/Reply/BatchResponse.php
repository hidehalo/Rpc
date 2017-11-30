<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\Json;
use Hidehalo\JsonRpc\Protocol\MessageInterface;

class BatchResponse
{
    private $replies;

    /**
     * @codeCoverageIgnore
     * @param array $replies
     */
    public function __construct(array $replies)
    {
        //TODO: add replies queue
        $this->replies = $replies;
    }

    public function reply($payload)
    {
        //TODO: impl
    }

    /**
     * @return string
     */
    public function __toString()
    {

        $asArray = $this->toArray();
       
        return Json::encode($asArray);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret = [];
        /**
         * @var MessageInterface $reply
         */
        foreach ($this->replies as $reply) {
            $ret[] = $reply->toArray();
        }

        return $ret;
    }
}