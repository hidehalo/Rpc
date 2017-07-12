<?php
namespace JsonRpc\Protocol;

trait MessageAwareTrait
{
    private $version = '2.0';
    private $id;
    
    public function getId()
    {
        return $this->id;
    }

    public function withId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }
}