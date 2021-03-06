<?php
namespace Hidehalo\JsonRpc\Protocol\Reply;

use Hidehalo\JsonRpc\Protocol\Json;
use Hidehalo\JsonRpc\Protocol\Reply\Response;
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
        $this->replies = $replies;
    }

    public function reply($id, $result)
    {
        $this->replies[] = new Response($id, $result);

        return $this;
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
            if ($reply instanceof MessageInterface) {
                $ret[] = $reply->toArray();
            } else {
                $ret[] = $reply;
            }
        }

        return $ret;
    }

    /**
     * Tell if no replies
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->replies);
    }

    /**
     * @return string
     */
    public function __toString()
    {

        $asArray = $this->toArray();
       
        return Json::encode($asArray);
    }
}