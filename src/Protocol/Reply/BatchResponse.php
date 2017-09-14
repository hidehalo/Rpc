<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\Json;
use Hidehalo\JsonRpc\Protocol\MessageInterface;

class BatchResponse
{
    private $replies;

    /**
     * @codeCoverageIgnore
     * @param string $replies
     */
    public function __construct($replies,Json $protocol)
    {
        $this->replies = $protocol->parseBatchResponses($replies);
    }

    /**
     * @return string
     */
    public function __toString()
    {

        $asArray = $this->toArray();
        //TODO: replace json_encode to Json::encode();
        return \Hidehalo\JsonRpc\Helper\json_encode();
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