<?php
namespace JsonRpc\Protocol;

interface RequestInterface extends MessageInterface
{
    public function withMethod($method);

    public function withParams($params);

    public function withExtras($extras);
}