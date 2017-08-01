<?php
namespace Hidehalo\JsonRpc\Protocol;

interface MessageInterface
{
    public function getVersion();

    public function withId($id);

    public function getId();

    public function __toString();

    public function toArray();
}