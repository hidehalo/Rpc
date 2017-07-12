<?php

trait MessageAwareTrait
{
    private $version = '2.0';
    private $id;
    private $result;
    
    public function getId()
    {
        return $this->id;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getVersion()
    {
        return $this->version;
    }
}