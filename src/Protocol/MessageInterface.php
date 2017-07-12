<?php
namespace JsonRpc\Protocol;

interface MessageInterface
{
    public function withId($id);

    public function __toString();

    public function toArray();
}